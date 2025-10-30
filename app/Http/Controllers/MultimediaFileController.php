<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MultimediaFile;
use Illuminate\Support\Str;
use Carbon\Carbon;
use ZipArchive;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\File; 

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
        
        // Usamos la ruta completa al disco público
        $diskPath = "multimedia/{$folderName}";
        $basePath = storage_path("app/public/{$diskPath}");

        // Crear carpeta si no existe
        if (!File::exists($basePath)) {
            File::makeDirectory($basePath, 0775, true);
        }

        $count = 0;

        // Subir imágenes individuales (Se mantiene correcto)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $filename = Str::uuid() . '.' . $img->getClientOriginalExtension();
                $img->move($basePath, $filename);
                $count++;
            }
        }

        // Subir carpeta ZIP
        if ($request->hasFile('folder')) {
            $zip = new ZipArchive;
            $zipPath = $request->file('folder')->getRealPath();

            if ($zip->open($zipPath) === true) {
                $zip->extractTo($basePath);
                $zip->close();
                
                // 🚨 CORRECCIÓN CLAVE: Usamos el iterador de directorios recursivo para contar imágenes.
                $count = 0;
                $directoryIterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($basePath, \RecursiveDirectoryIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::SELF_FIRST
                );
                
                // Expresión regular para buscar archivos de imagen
                $imagePattern = '/\.(png|jpg|jpeg)$/i';

                foreach ($directoryIterator as $file) {
                    if ($file->isFile() && preg_match($imagePattern, $file->getFilename())) {
                        $count++;
                    }
                }
            }
        }

        // ... (El resto del código de store se mantiene correcto)
        $relativePath = "multimedia/{$folderName}";

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
        
        // Buscamos las imágenes en 'storage/app/public/ruta'
        $imagesPath = storage_path("app/public/{$study->study_uri}"); 
        
        // 🚨 CAMBIO CRÍTICO: Usamos el iterador de directorios para buscar recursivamente
        // Esto asegura que encontramos imágenes incluso si el ZIP creó una subcarpeta.
        $imageUrls = [];
        if (File::isDirectory($imagesPath)) {
             $directoryIterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($imagesPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );
            
            $imagePattern = '/\.(png|jpg|jpeg)$/i';

            foreach ($directoryIterator as $file) {
                if ($file->isFile() && preg_match($imagePattern, $file->getFilename())) {
                    // Reemplazamos la ruta base de storage/app/public por la ruta de enlace simbólico (storage/)
                    $relativePath = str_replace(storage_path('app/public/'), '', $file->getPathname());
                    $imageUrls[] = Storage::url($relativePath); // Genera la URL pública: /storage/multimedia/CODIGO/imagen.jpg
                }
            }
        }

        return view('multimedia.show', compact('study', 'imageUrls'));
    }

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
