<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\MultimediaFile;
use App\Models\Patient;
use Illuminate\Support\Facades\File;
use ZipArchive;

class MultimediaFileController extends Controller
{

    protected $basePath = 'multimedia';

    public function index(): View
    {
        $files = MultimediaFile::get();
        return view('multimedia.index', compact('radiographies'));
    }
    public function create(): View
    {
        $patients = Patient::all();
        return view('multimedia.create', compact('patients'));
    }
    public function edit(MultimediaFile $file): View
    {
        $patients = Patient::all();
        return view('multimedia.edit', compact('file', 'patients'));
    }

    public function store(Request $request)
    {
        // 1. Validación (CRÍTICA: Usar 'file.*' para validar cada elemento del array)
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'study_type' => 'required|string|in:radiography,tomography,ecography,general',
            'notes' => 'nullable|string|max:1000',

            // Regla corregida:
            // 'file.*' valida cada archivo subido
            'file.*' => [
                'required',
                'file', // Asegura que es un archivo
                'max:102400', // Máximo 100MB (ajusta si es necesario)
                // Permitimos imágenes y ZIP.
                'mimes:jpg,jpeg,png,gif,zip',
            ],
        ]);

        // 2. Procesamiento de archivos
        // El resto de tu lógica para guardar los archivos
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $uploadedFile) {

                // Determina si es un ZIP para extraerlo o una imagen para guardarla
                if ($uploadedFile->getClientOriginalExtension() === 'zip') {
                    // Lógica para manejar y extraer ZIP
                    // ...
                } else {
                    // Lógica para guardar la imagen
                    // $path = $uploadedFile->store('multimedia/' . $request->patient_id);
                    // ... crear registro en DB ...
                }
            }
        }

        // 3. Redirección
        return redirect()->route('multimedia.index')->with('success', 'Archivos multimedia subidos con éxito.');
    }

    /**
     * Procesa un archivo ZIP, extrayendo imágenes y guardando metadatos.
     */
    protected function processZipFile($zipFile, $destinationPath, $patient, $studyType)
    {
        // 1. Guardar el zip temporalmente para poder abrirlo
        $tempZipName = time() . '_' . uniqid() . '.zip';
        $zipFile->move(sys_get_temp_dir(), $tempZipName);
        $zipPath = sys_get_temp_dir() . '/' . $tempZipName;

        $baseFolderName = pathinfo($zipFile->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time();
        $extractPath = sys_get_temp_dir() . '/' . $baseFolderName; // Extraemos a una carpeta TEMP

        // Crear carpeta de extracción temporal
        File::makeDirectory($extractPath, 0777, true, true);

        $zip = new ZipArchive;
        if ($zip->open($zipPath) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();

            // 2. Buscar solo archivos de imagen válidos dentro de la carpeta extraída
            $extractedFiles = File::allFiles($extractPath);

            foreach ($extractedFiles as $file) {
                if ($file->isFile() && in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png'])) {
                    // Generamos un nuevo nombre único para el archivo movido
                    $newFileName = time() . '_' . uniqid() . '.' . $file->getExtension();

                    // 3. Movemos el archivo de la carpeta temporal a la carpeta FINAL (public/multimedia)
                    File::move($file->getRealPath(), $destinationPath . '/' . $newFileName);

                    // 4. Registramos los metadatos de la imagen individual
                    $this->saveMetadata(
                        $file->getFilename(),
                        $newFileName,
                        $file->getSize(),
                        mime_content_type($destinationPath . '/' . $newFileName),
                        $patient,
                        $studyType
                    );
                }
            }

            // 5. Eliminar la carpeta temporal de extracción
            File::deleteDirectory($extractPath);

            // 6. Guardar un registro del archivo ZIP original (como carpeta/grupo)
            $this->saveMetadata(
                $zipFile->getClientOriginalName() . ' (ZIP)',
                'folder_' . $baseFolderName,
                $zipFile->getSize(),
                'folder',
                $patient,
                $studyType
            );
        } else {
            // Manejar error de ZIP 
            // \Log::error("Error al abrir o procesar el archivo ZIP: " . $zipFile->getClientOriginalName());
        }

        // 7. Eliminar el archivo ZIP temporal subido
        File::delete($zipPath);
    }


    /**
     * Muestra un archivo o el contenido de una carpeta.
     */
    public function show(MultimediaFile $file): View
    {
        return view('multimedia.show', compact('file'));
    }
    public function tool(MultimediaFile $file): View
    {
        return view('multimedia.tool', compact('file'));
    }
    public function report(MultimediaFile $file): View
    {
        return view('multimedia.report', compact('file'));
    }

    public function measurements(MultimediaFile $file): View
    {
        return view('multimedia.measurements', compact('file'));
    }

    public function destroy(MultimediaFile $file): RedirectResponse
    {
        // Si no es un registro de 'folder', intenta eliminar el archivo físico
        if ($file->file_type !== 'folder') {
            $filePath = public_path($file->file_path);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $file->delete();
        return redirect()->route('multimedia.index')->with('danger', 'Archivo eliminado.');
    }

    /**
     * Busca archivos multimedia por nombre, CI del paciente o tipo de estudio.
     */
    public function search(Request $request): View
    {
        $search = $request->input('search');
        $files = MultimediaFile::where('file_name', 'LIKE', '%' . $search . '%')
            ->orWhere('ci_patient', 'LIKE', '%' . $search . '%')
            ->orWhere('study_type', 'LIKE', '%' . $search . '%')
            ->get();

        return view('multimedia.search', compact('files'));
    }
}
