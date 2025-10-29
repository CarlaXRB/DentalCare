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
        $multimediaFiles = MultimediaFile::with('patient')->get(); // Optimizamos con la relación patient
        return view('multimedia.index', compact('multimediaFiles')); // Cambiado a $multimediaFiles
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

    public function store(Request $request): RedirectResponse
    {
        // 1. Validación (CRÍTICA: Usar 'file.*' para validar cada elemento del array)
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'study_type' => 'required|string|in:radiography,tomography,ecography,general',
            'notes' => 'nullable|string|max:1000',

            // Regla corregida:
            'file.*' => [
                'required',
                'file',
                'max:102400', // Máximo 100MB
                'mimes:jpg,jpeg,png,gif,zip,pdf', // Agregué pdf por si acaso
            ],
        ]);
        
        $patient = Patient::findOrFail($request->patient_id);
        $studyType = $request->study_type;
        $destinationPath = public_path('storage/' . $this->basePath);
        
        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }

        // 2. Procesamiento de archivos
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $uploadedFile) {

                if ($uploadedFile->getClientOriginalExtension() === 'zip') {
                    // Lógica para manejar y extraer ZIP
                    $this->processZipFile($uploadedFile, $destinationPath, $patient, $studyType);
                } else {
                    // Lógica para guardar la imagen/archivo individual
                    $newFileName = time() . '_' . uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
                    
                    // Mover el archivo subido a la carpeta de destino final
                    $uploadedFile->move($destinationPath, $newFileName);

                    // OBTENER EL MIME TYPE CORRECTO DESPUÉS DE MOVERLO
                    $fullFilePath = $destinationPath . '/' . $newFileName;
                    $mimeType = File::mimeType($fullFilePath);

                    $this->saveMetadata(
                        $uploadedFile->getClientOriginalName(),
                        $newFileName,
                        $uploadedFile->getSize(),
                        $mimeType,
                        $patient,
                        $studyType
                    );
                }
            }
        }

        // 3. Redirección
        return redirect()->route('multimedia.index')->with('success', 'Archivos multimedia subidos con éxito.');
    }

    /**
     * Guarda el registro del archivo en la base de datos.
     */
    protected function saveMetadata($originalName, $filePath, $fileSize, $mimeType, Patient $patient, $studyType): void
    {
        // Generamos un ID de estudio único
        $studyId = 'STUDY-' . time() . rand(100, 999);
        
        MultimediaFile::create([
            'patient_id' => $patient->id,
            'name_patient' => $patient->name . ' ' . $patient->lastname,
            'ci_patient' => $patient->ci,
            'study_type' => $studyType,
            'file_name' => $originalName,
            'file_type' => $mimeType,
            'file_size' => $fileSize,
            'file_path' => $filePath, // Solo guardamos el nombre del archivo si es individual
            'radiography_id' => $studyId,
            'radiography_date' => now()->toDateString(), // Usamos la fecha actual de subida
        ]);
    }


    /**
     * Procesa un archivo ZIP, extrayendo imágenes y guardando metadatos.
     */
    protected function processZipFile($zipFile, $destinationPath, $patient, $studyType): void
    {
        // 1. Guardar el zip temporalmente para poder abrirlo
        $tempZipName = time() . '_' . uniqid() . '.zip';
        $zipFile->move(sys_get_temp_dir(), $tempZipName);
        $zipPath = sys_get_temp_dir() . '/' . $tempZipName;

        $baseFolderName = pathinfo($zipFile->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time();
        // La carpeta de extracción final será dentro de storage/multimedia/nombre_carpeta_zip
        $extractDestination = $destinationPath . '/' . $baseFolderName; 

        // Crear carpeta de extracción final
        File::makeDirectory($extractDestination, 0777, true, true);

        $zip = new ZipArchive;
        if ($zip->open($zipPath) === TRUE) {
            $zip->extractTo($extractDestination);
            $zip->close();

            // 2. Buscar solo archivos de imagen válidos dentro de la carpeta extraída
            $extractedFiles = File::allFiles($extractDestination);
            $isFolderRegistered = false;

            foreach ($extractedFiles as $file) {
                // $file es SplFileInfo, no UploadedFile. Usamos getExtension() y getMimeType() de la clase File.
                if ($file->isFile() && in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'pdf'])) {
                    
                    // OBTENER EL MIME TYPE USANDO LA RUTA COMPLETA DEL ARCHIVO EXTRAÍDO (¡CORRECCIÓN AQUÍ!)
                    $mimeType = File::mimeType($file->getRealPath());

                    $filePath = $baseFolderName . '/' . $file->getFilename();

                    $this->saveMetadata(
                        $file->getFilename(),
                        $filePath, // Path relativo a storage/multimedia/
                        $file->getSize(),
                        $mimeType,
                        $patient,
                        $studyType
                    );
                    $isFolderRegistered = true;
                }
            }

            // 3. Registrar la carpeta si se encontraron archivos dentro
            if ($isFolderRegistered) {
                 // Guardar un registro del archivo ZIP original (como carpeta/grupo)
                MultimediaFile::create([
                    'patient_id' => $patient->id,
                    'name_patient' => $patient->name . ' ' . $patient->lastname,
                    'ci_patient' => $patient->ci,
                    'study_type' => $studyType . ' (ZIP Group)',
                    'file_name' => $baseFolderName,
                    'file_type' => 'folder',
                    'file_size' => $zipFile->getSize(),
                    'file_path' => $baseFolderName, // Path de la carpeta
                    'radiography_id' => 'GROUP-' . time() . rand(100, 999),
                    'radiography_date' => now()->toDateString(), 
                ]);
            } else {
                // Si no se encontraron archivos válidos, eliminar la carpeta vacía
                File::deleteDirectory($extractDestination);
            }
        } else {
            // Manejar error de ZIP 
             // Aquí podrías redirigir con un error si el ZIP falla
        }

        // 4. Eliminar el archivo ZIP temporal subido
        File::delete($zipPath);
    }


    /**
     * Muestra un archivo o el contenido de una carpeta.
     */
    public function show(MultimediaFile $file): View
    {
        $file->load('patient'); // Aseguramos la relación
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
        $fullPath = public_path('storage/multimedia/' . $file->file_path);
        
        // Si es un registro de 'folder', intentamos eliminar la carpeta completa
        if ($file->file_type === 'folder') {
            if (File::exists($fullPath)) {
                 File::deleteDirectory($fullPath);
            }
            // Además, eliminamos todos los archivos relacionados a esta carpeta (opcional, pero recomendado)
            MultimediaFile::where('file_path', 'LIKE', $file->file_path . '/%')->delete();
        } 
        // Si no es un registro de 'folder', es un archivo individual
        else {
            if (File::exists($fullPath)) {
                File::delete($fullPath);
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
            ->with('patient') // Cargamos la relación patient
            ->get();

        // ----------------------------------------------------
        // CORRECCIÓN AQUÍ: Usamos la sintaxis de array simple
        // para pasar la variable con el nombre que la vista espera.
        // ----------------------------------------------------
        return view('multimedia.index', ['multimediaFiles' => $files]);
        // Alternativa: return view('multimedia.index', compact('files')); y cambiar la vista a usar $files
        // Pero mantendremos el nombre $multimediaFiles para consistencia con index()
    }
}
