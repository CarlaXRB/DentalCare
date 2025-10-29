<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\MultimediaFileRequest;
use App\Models\MultimediaFile;
use App\Models\Patient;
use Illuminate\Support\Facades\File;
use ZipArchive; // Necesario para manejar archivos comprimidos

class MultimediaFileController extends Controller
{
    // Carpeta de destino dentro de public/, asegurando acceso directo por URL.
    protected $basePath = 'multimedia'; 

    /**
     * Muestra la lista de archivos cargados.
     */
    public function index(): View
    {
        // Se asume que has definido una relación 'patient' en el modelo MultimediaFile
        $files = MultimediaFile::with('patient')->latest()->get();
        return view('multimedia.index', compact('files'));
    }

    /**
     * Muestra el formulario para subir archivos.
     */
    public function create(): View
    {
        $patients = Patient::all();
        return view('multimedia.create', compact('patients'));
    }

    /**
     * Almacena uno o varios archivos (o un archivo ZIP) y sus metadatos.
     */
    public function store(MultimediaFileRequest $request): RedirectResponse
    {
        // Usamos ci_patient para encontrar al paciente.
        $patient = Patient::where('ci_patient', $request->ci_patient)->firstOrFail();
        
        $destinationPath = public_path($this->basePath);
        // Crear el directorio si no existe
        if (!File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0777, true, true);
        }

        foreach ($request->file('file') as $uploadedFile) {
            $extension = strtolower($uploadedFile->getClientOriginalExtension());
            $originalName = $uploadedFile->getClientOriginalName();
            
            // Nombre de archivo único
            $uniqueFileName = time() . '_' . uniqid() . '.' . $extension;
            
            if ($extension === 'zip') {
                // Lógica para manejar archivos ZIP
                $this->processZipFile($uploadedFile, $destinationPath, $patient, $request->study_type);
            } else {
                // Lógica para archivos de imagen individuales (JPG, PNG)
                // Usamos move() para guardar directamente en la carpeta public/multimedia
                $uploadedFile->move($destinationPath, $uniqueFileName);
                
                // Guardamos los metadatos
                $this->saveMetadata($originalName, $uniqueFileName, $uploadedFile->getSize(), $uploadedFile->getClientMimeType(), $patient, $request->study_type);
            }
        }

        return redirect()->route('multimedia.index')->with('success', 'Archivos procesados y subidos exitosamente.');
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
     * Guarda los metadatos de un archivo en la base de datos.
     */
    protected function saveMetadata(string $originalName, string $storedName, int $size, string $mimeType, Patient $patient, string $studyType)
    {
        $file = new MultimediaFile();
        $file->name_patient = $patient->name_patient;
        $file->ci_patient = $patient->ci_patient;
        $file->file_name = $originalName; // Nombre original para mostrar
        
        // La ruta es la carpeta base + nombre (porque está en public)
        $file->file_path = ($mimeType === 'folder') ? $storedName : $this->basePath . '/' . $storedName; 
        
        $file->file_type = $mimeType;
        $file->study_type = $studyType;
        $file->size = $size;
        $file->save();
    }

    /**
     * Muestra un archivo o el contenido de una carpeta.
     */
    public function show(MultimediaFile $file): View
    {
        return view('multimedia.show', compact('file'));
    }

    /**
     * Elimina un archivo o un registro de carpeta y su archivo físico (si existe).
     */
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
