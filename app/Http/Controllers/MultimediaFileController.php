<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MultimediaFile;
use Illuminate\Support\Str;
use Carbon\Carbon;
use ZipArchive;
use Illuminate\Support\Facades\File; // Usaremos File para operaciones de directorio

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
        
        // 🚨 CAMBIO CLAVE 1: Volvemos a guardar en public/multimedia
        $diskPath = "multimedia/{$folderName}";
        $basePath = public_path($diskPath);

        // Crear carpeta si no existe
        if (!File::exists($basePath)) {
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

        // Subir carpeta ZIP
        if ($request->hasFile('folder')) {
            $zip = new ZipArchive;
            $zipPath = $request->file('folder')->getRealPath();

            if ($zip->open($zipPath) === true) {
                $zip->extractTo($basePath);
                $zip->close();
                
                // Conteo recursivo (se mantiene la lógica que funciona)
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
        
        // 🚨 CAMBIO CLAVE 2: Buscamos las imágenes en 'public/ruta'
        $imagesPath = public_path($study->study_uri); 
        
        $imageUrls = [];
        if (File::isDirectory($imagesPath)) {
            $directoryIterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($imagesPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );
            
            $imagePattern = '/\.(png|jpg|jpeg)$/i';

            foreach ($directoryIterator as $file) {
                if ($file->isFile() && preg_match($imagePattern, $file->getFilename())) {
                    // Generamos la URL usando asset() directamente con la ruta relativa del estudio.
                    // Esto funciona mejor en la mayoría de los hosts que no tienen storage:link configurado.
                    $imageUrls[] = asset($study->study_uri . '/' . $file->getFilename()); 
                }
            }
        }

        return view('multimedia.show', compact('study', 'imageUrls'));
    }

    public function destroy($id)
    {
        $study = MultimediaFile::findOrFail($id);
        
        // 🚨 CAMBIO CLAVE 3: Apuntamos al directorio en 'public'
        $dir = public_path($study->study_uri); 

        if (File::isDirectory($dir)) {
            File::deleteDirectory($dir);
        }

        $study->delete();

        return redirect()->route('multimedia.index')->with('success', 'Estudio eliminado correctamente.');
    }
}
