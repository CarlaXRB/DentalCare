<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MultimediaFile;
use Illuminate\Support\Str;
use Carbon\Carbon;
use ZipArchive;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Storage; // ¡Necesario para el Paso 3!

class MultimediaFileController extends Controller
{
    // ... (index y create se mantienen sin cambios)

    public function index()
    {
        $studies = MultimediaFile::with('patient')->latest()->get();
        return view('multimedia.index', compact('studies'));
    }

    public function create()
    {
        return view('multimedia.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ci_patient' => 'required|exists:patients,ci_patient',
            'study_type' => 'required|string',
            'images.*' => 'nullable|mimes:png,jpg,jpeg|max:10240',
            'folder' => 'nullable|file|mimetypes:application/zip,application/x-zip-compressed'
        ]);

        $studyCode = strtoupper(Str::random(8));
        $studyDate = Carbon::now()->toDateString();
        $folderName = "{$studyCode}_{$studyDate}";
        
        // 🚨 CAMBIO CLAVE 1: Usamos la ruta storage/app/public/multimedia (donde sí hay permisos)
        $diskPath = "multimedia/{$folderName}";
        $basePath = storage_path("app/public/{$diskPath}");

        // Crear carpeta si no existe
        if (!File::exists($basePath)) {
            // Este mkdir funcionará porque apunta a la carpeta 'storage'
            File::makeDirectory($basePath, 0775, true);
        }

        $count = 0;

        // Subir imágenes individuales
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $filename = Str::uuid() . '.' . $img->getClientOriginalExtension();
                $img->move($basePath, $filename);
                $count++;
            }
        }

        // Subir carpeta ZIP (lógica de conteo recursiva se mantiene)
        if ($request->hasFile('folder')) {
            $zip = new ZipArchive;
            $zipPath = $request->file('folder')->getRealPath();

            if ($zip->open($zipPath) === true) {
                $zip->extractTo($basePath);
                $zip->close();
                
                $count = 0;
                if (File::isDirectory($basePath)) {
                    $directoryIterator = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($basePath, \RecursiveDirectoryIterator::SKIP_DOTS),
                        \RecursiveIteratorIterator::SELF_FIRST
                    );
                    $imagePattern = '/\.(png|jpg|jpeg)$/i';
                    foreach ($directoryIterator as $file) {
                        if ($file->isFile() && preg_match($imagePattern, $file->getFilename())) {
                            $count++;
                        }
                    }
                }
            }
        }

        // Ruta relativa que se guarda en la base de datos (Ej: multimedia/CODIGO_FECHA)
        $relativePath = $diskPath; 

        MultimediaFile::create([
            'ci_patient' => $request->ci_patient,
            'study_code' => $studyCode,
            'study_date' => $studyDate,
            'study_type' => $request->study_type,
            'study_uri' => $relativePath, 
            'description' => $request->input('description'),
            'image_count' => $count,
        ]);

        return redirect()->route('multimedia.index')->with('success', 'Estudio cargado correctamente.');
    }

    public function show($id)
    {
        $study = MultimediaFile::findOrFail($id);
        
        // 🚨 CAMBIO CLAVE 2: Ahora buscamos las imágenes en 'storage/app/public/ruta'
        $imagesPath = storage_path("app/public/{$study->study_uri}"); 
        
        $imageUrls = [];
        if (File::isDirectory($imagesPath)) {
            $directoryIterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($imagesPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );
            
            $imagePattern = '/\.(png|jpg|jpeg)$/i';

            foreach ($directoryIterator as $file) {
                if ($file->isFile() && preg_match($imagePattern, $file->getFilename())) {
                    // 🚨 CAMBIO CLAVE 3: Generamos una ruta protegida hacia un NUEVO CONTROLADOR (ver Paso 3)
                    $imageUrls[] = route('multimedia.image', [
                        'studyCode' => $study->study_code, 
                        'fileName' => $file->getFilename()
                    ]); 
                }
            }
        }

        return view('multimedia.show', compact('study', 'imageUrls'));
    }
    
    // Función para servir la imagen (Paso 3)
    public function serveImage($studyCode, $fileName)
    {
        // 1. Encontramos el estudio para obtener la study_uri
        $study = MultimediaFile::where('study_code', $studyCode)->firstOrFail();
        
        // 2. Construimos la ruta completa en el disco (storage/app/public/multimedia/CODIGO_FECHA/imagen.jpg)
        $path = storage_path("app/public/{$study->study_uri}/{$fileName}");

        // 3. Verificamos que el archivo existe y es una imagen
        if (!File::exists($path) || !preg_match('/\.(png|jpg|jpeg)$/i', $fileName)) {
            abort(404);
        }

        // 4. Devolvemos el archivo directamente al navegador
        return response()->file($path);
    }

    // ... (destroy se mantiene, pero apunta a storage)

    public function destroy($id)
    {
        $study = MultimediaFile::findOrFail($id);
        
        // Apuntamos al directorio en 'storage'
        $dir = storage_path("app/public/{$study->study_uri}"); 

        if (File::isDirectory($dir)) {
            File::deleteDirectory($dir);
        }

        $study->delete();

        return redirect()->route('multimedia.index')->with('success', 'Estudio eliminado correctamente.');
    }
}
