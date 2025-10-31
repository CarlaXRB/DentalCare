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

    public function store(Request $request)
    {
        // 1️⃣ Validar los datos del formulario
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'study_type' => 'required|string',
            'images.*' => 'nullable|mimes:png,jpg,jpeg|max:10240',
            'folder' => 'nullable|file|mimetypes:application/zip,application/x-zip-compressed',
            'description' => 'nullable|string'
        ]);

        // 2️⃣ Buscar paciente
        $patient = Patient::findOrFail($request->patient_id);

        // 3️⃣ Definir carpeta de destino en /public/multimedia
        $studyCode = strtoupper(Str::random(8));
        $studyDate = now()->format('Y-m-d');
        $folderName = "{$studyCode}_{$studyDate}";

        $destinationPath = public_path("multimedia/{$folderName}");

        // Crear carpeta si no existe
        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }

        // 4️⃣ Procesar imágenes sueltas
        $imageCount = 0;
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $fileName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $image->move($destinationPath, $fileName);
                $imageCount++;
            }
        }

        // 5️⃣ Procesar carpeta ZIP
        if ($request->hasFile('folder')) {
            $zip = new ZipArchive;
            $zipPath = $request->file('folder')->getRealPath();

            if ($zip->open($zipPath) === true) {
                $zip->extractTo($destinationPath);
                $zip->close();
            }

            // Contar imágenes extraídas
            $imageCount = 0;
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($destinationPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );
            foreach ($iterator as $file) {
                if ($file->isFile() && preg_match('/\.(png|jpg|jpeg)$/i', $file->getFilename())) {
                    $imageCount++;
                }
            }
        }

        // 6️⃣ Crear registro en la base de datos
        $multimedia = new MultimediaFile();
        $multimedia->name_patient = $patient->name_patient;
        $multimedia->ci_patient = $patient->ci_patient;
        $multimedia->study_code = $studyCode;
        $multimedia->study_date = $studyDate;
        $multimedia->study_type = $request->study_type;
        $multimedia->study_uri = "multimedia/{$folderName}";
        $multimedia->description = $request->description;
        $multimedia->image_count = $imageCount;
        $multimedia->save();

        return redirect()->route('multimedia.index')
            ->with('success', 'Estudio multimedia cargado correctamente.');
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
            ->orWhere('study_code', 'LIKE', '%' . $search . '%')->get();
        return view('multimedia.search', compact('files'));
    }
}
