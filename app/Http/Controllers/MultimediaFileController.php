<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MultimediaFile;
use App\Models\Patient;
use Illuminate\Support\Str;
use Carbon\Carbon;
use ZipArchive;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class MultimediaFileController extends Controller
{
    public function index()
    {
        $studies = MultimediaFile::with('patient')->latest()->get();
        return view('multimedia.index', compact('studies'));
    }
    public function create()
    {
        $patients = Patient::all();
        return view('multimedia.create', compact('patients'));
    }
    public function edit(MultimediaFile $multimedia)
    {
        return view('multimedia.edit', compact('multimedia'));
    }

    public function update(Request $request, MultimediaFile $multimedia)
    {
        // 1. Validar datos
        $validated = $request->validate([
            'name_patient' => 'required|string|max:255',
            'ci_patient' => 'required|max:50',
            'study_type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // 2. Llenar el modelo con los datos validados ANTES de la comprobación
        $multimedia->fill($validated);

        // 3. Verificar si realmente hay cambios usando isDirty() sin argumentos
        if ($multimedia->isDirty()) {
            $multimedia->save();

            return redirect()
                ->route('multimedia.index')
                ->with('success', 'Información del estudio actualizada correctamente.');
        }

        return redirect()
            ->route('multimedia.index')
            ->with('info', 'No se detectaron cambios en la información.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_patient' => 'required|string',
            'ci_patient' => 'required',
            'study_type' => 'required|string',
            'images.*' => 'nullable|mimes:png,jpg,jpeg|max:10240',
            'folder' => 'nullable|file|mimetypes:application/zip,application/x-zip-compressed'
        ]);

        $studyCode = strtoupper(Str::random(8));
        $studyDate = Carbon::now()->toDateString();
        $folderName = "{$studyCode}_{$studyDate}";

        $diskPath = "multimedia/{$folderName}";
        $basePath = storage_path("app/public/{$diskPath}");

        if (!File::exists($basePath)) {
            File::makeDirectory($basePath, 0775, true);
        }

        $count = 0;

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $filename = Str::uuid() . '.' . $img->getClientOriginalExtension();
                $img->move($basePath, $filename);
                $count++;
            }
        }

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

        $relativePath = $diskPath;

        MultimediaFile::create([
            'name_patient' => $request->name_patient,
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
        $diskRootPath = storage_path("app/public/{$study->study_uri}");

        $imageUrls = [];

        if (File::isDirectory($diskRootPath)) {
            $directoryIterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($diskRootPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            $imagePattern = '/\.(png|jpg|jpeg)$/i';

            foreach ($directoryIterator as $file) {
                if ($file->isFile() && preg_match($imagePattern, $file->getFilename())) {
                    $fullPath = $file->getPathname();
                    $relativePathToFile = substr($fullPath, strlen($diskRootPath) + 1);
                    $imageUrls[] = route('multimedia.image', [
                        'studyCode' => $study->study_code,
                        'fileName' => $relativePathToFile
                    ]);
                }
            }
        }

        return view('multimedia.show', compact('study', 'imageUrls'));
    }

    public function serveImage($studyCode, $fileName)
    {
        $study = MultimediaFile::where('study_code', $studyCode)->firstOrFail();
        $path = storage_path("app/public/{$study->study_uri}/{$fileName}");
        if (!File::exists($path)) {
            abort(404);
        }
        return response()->file($path);
    }

    public function destroy($id)
    {
        $study = MultimediaFile::findOrFail($id);
        $dir = storage_path("app/public/{$study->study_uri}");
        if (File::isDirectory($dir)) {
            File::deleteDirectory($dir);
        }
        $study->delete();
        return redirect()->route('multimedia.index')->with('success', 'Estudio eliminado correctamente.');
    }
    public function search(Request $request)
    {
        $search = $request->input('search');
        $files = MultimediaFile::where('ci_patient', 'LIKE', '%' . $search . '%')
            ->orWhere('study_date', 'LIKE', '%' . $search . '%')
            ->orWhere('name_patient', 'LIKE', '%' . $search . '%')->get();
        return view('multimedia.search', compact('files'));
    }
    public function measure($id)
    {
        $study = MultimediaFile::findOrFail($id);

        $diskRootPath = storage_path("app/public/{$study->study_uri}");
        $imageUrls = [];

        if (File::isDirectory($diskRootPath)) {
            $directoryIterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($diskRootPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            $imagePattern = '/\.(png|jpg|jpeg)$/i';

            foreach ($directoryIterator as $file) {
                if ($file->isFile() && preg_match($imagePattern, $file->getFilename())) {
                    $fullPath = $file->getPathname();
                    $relativePathToFile = substr($fullPath, strlen($diskRootPath) + 1);
                    $imageUrls[] = route('multimedia.image', [
                        'studyCode' => $study->study_code,
                        'fileName' => $relativePathToFile
                    ]);
                }
            }
        }

        return view('multimedia.measure', compact('study', 'imageUrls'));
    }
    public function tool($id)
    {
        $study = MultimediaFile::findOrFail($id);

        $diskRootPath = storage_path("app/public/{$study->study_uri}");
        $imageUrls = [];

        if (File::isDirectory($diskRootPath)) {
            $directoryIterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($diskRootPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            $imagePattern = '/\.(png|jpg|jpeg)$/i';

            foreach ($directoryIterator as $file) {
                if ($file->isFile() && preg_match($imagePattern, $file->getFilename())) {
                    $fullPath = $file->getPathname();
                    $relativePathToFile = substr($fullPath, strlen($diskRootPath) + 1);
                    $imageUrls[] = route('multimedia.image', [
                        'studyCode' => $study->study_code,
                        'fileName' => $relativePathToFile
                    ]);
                }
            }
        }

        return view('multimedia.measure', compact('study', 'imageUrls'));
    }
}
