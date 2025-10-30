<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MultimediaFile;
use Illuminate\Support\Str;
use Carbon\Carbon;
use ZipArchive;

class MultimediaFileController extends Controller
{
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
        $basePath = public_path("multimedia/{$folderName}");

        if (!file_exists($basePath)) {
            mkdir($basePath, 0775, true);
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

                // Contar imágenes válidas
                $allFiles = glob($basePath . '/*');
                $count = count(array_filter($allFiles, function ($file) {
                    return preg_match('/\.(png|jpg|jpeg)$/i', $file);
                }));
            }
        }

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
        $imagesPath = public_path($study->study_uri);
        $images = glob($imagesPath . '/*.{png,jpg,jpeg}', GLOB_BRACE);

        // Convertir rutas absolutas en URLs accesibles
        $imageUrls = array_map(function ($path) {
            return asset(str_replace(public_path(), '', $path));
        }, $images);

        return view('multimedia.show', compact('study', 'imageUrls'));
    }

    public function destroy($id)
    {
        $study = MultimediaFile::findOrFail($id);
        $dir = public_path($study->study_uri);

        if (is_dir($dir)) {
            $files = glob($dir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($dir);
        }

        $study->delete();

        return redirect()->route('multimedia.index')->with('success', 'Estudio eliminado correctamente.');
    }
}
