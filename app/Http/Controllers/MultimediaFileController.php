<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MultimediaFile;
use Illuminate\Support\Str;
use Carbon\Carbon;
use ZipArchive;
use Illuminate\Support\Facades\Storage; // ¡Importamos el Facade Storage!
use Illuminate\Support\Facades\File; // ¡Importamos el Facade File para operaciones de directorio!

class MultimediaFileController extends Controller
{
    // La función index se mantiene bien
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
        
        // 🚨 CAMBIO: Definimos la ruta de la carpeta dentro de 'storage/app/public' 
        // y usamos el disco 'public' para operaciones futuras
        $diskPath = "multimedia/{$folderName}";
        $basePath = storage_path("app/public/{$diskPath}");

        // Crear carpeta si no existe (usando File para consistencia)
        if (!File::exists($basePath)) {
            File::makeDirectory($basePath, 0775, true);
        }

        $count = 0;

        // Subir imágenes individuales
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $filename = Str::uuid() . '.' . $img->getClientOriginalExtension();
                // 🚨 CAMBIO: Usamos move() hacia el $basePath correcto
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

                // Contar imágenes válidas
                $allFiles = File::glob($basePath . '/*');
                $count = count(array_filter($allFiles, function ($file) {
                    return preg_match('/\.(png|jpg|jpeg)$/i', $file);
                }));
            }
        }

        // 📁 Ruta relativa que guardamos en la base de datos (Ej: multimedia/CODIGO_FECHA)
        $relativePath = "multimedia/{$folderName}";

        MultimediaFile::create([
            'ci_patient' => $request->ci_patient,
            'study_code' => $studyCode,
            'study_date' => $studyDate,
            'study_type' => $request->study_type,
            'study_uri' => $relativePath, // Esta ruta es la clave
            'description' => $request->input('description'),
            'image_count' => $count,
        ]);

        return redirect()->route('multimedia.index')->with('success', 'Estudio cargado correctamente.');
    }

    public function show($id)
    {
        $study = MultimediaFile::findOrFail($id);
        
        // 🚨 CAMBIO CRÍTICO: Ahora buscamos las imágenes en 'storage/app/public/ruta'
        $imagesPath = storage_path("app/public/{$study->study_uri}"); 
        $images = File::glob($imagesPath . '/*.{png,jpg,jpeg}', GLOB_BRACE);

        // Convertir rutas absolutas del STORAGE a URLs públicas usando Storage::url()
        $imageUrls = array_map(function ($path) use ($study) {
            // Reemplazamos la ruta base de storage/app/public por la ruta de enlace simbólico (storage/)
            $relativePath = str_replace(storage_path('app/public/'), '', $path);
            return Storage::url($relativePath); // Genera la URL pública: /storage/multimedia/CODIGO/imagen.jpg
        }, $images);

        return view('multimedia.show', compact('study', 'imageUrls'));
    }

    public function destroy($id)
    {
        $study = MultimediaFile::findOrFail($id);
        
        // 🚨 CAMBIO CRÍTICO: Apuntamos al directorio en 'storage'
        $dir = storage_path("app/public/{$study->study_uri}"); 

        if (File::isDirectory($dir)) {
            // Eliminamos el directorio y su contenido
            File::deleteDirectory($dir);
        }

        $study->delete();

        return redirect()->route('multimedia.index')->with('success', 'Estudio eliminado correctamente.');
    }
}
