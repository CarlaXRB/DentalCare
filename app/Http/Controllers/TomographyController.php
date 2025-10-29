<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;
use App\Http\Requests\TomographyRequest;
use App\Models\Tomography;
use App\Models\Patient;
use ZipArchive;

class TomographyController extends Controller
{
    public function index():View{
        $tomographies = tomography::get();
        return view('tomography.index', compact('tomographies'));
    }
    public function new():View{
        return view('tomography.newtomography');
    }
    public function create():View{
        $patients = Patient::all();
        return view('tomography.create', compact('patients'));
    }
    public function edit(Tomography $tomography):View{
        $patients = Patient::all();
        return view('tomography.edit', compact('tomography','patients'));
    }
    public function update(TomographyRequest $request, Tomography $tomography):RedirectResponse{
        $patient = Patient::findOrFail($request->patient_id);
        $tomography->name_patient=$patient->name_patient;
        $tomography->ci_patient=$patient->ci_patient;
        $tomography->update([
            'tomography_id' => $request->input('tomography_id'),
            'tomography_date' => $request->input('tomography_date'),
            'tomography_type' => $request->input('tomography_type'),
            'tomography_doctor' => $request->input('tomography_doctor'),
            'tomography_charge' => $request->input('tomography_charge'),
        ]);
        return redirect()->route('tomography.index')->with('success','Información actualizada');
    }
    public function tool(Tomography $tomography):View{
        return view('tomography.tool', compact('tomography'));
    }
    public function measurements(Tomography $tomography):View{
        return view('tomography.measurements', compact('tomography'));
    }
    
    public function store(TomographyRequest $request): RedirectResponse{
        $patient = Patient::findOrFail($request->patient_id);
        $imageDicomUri = null; // Ruta de la carpeta o archivo individual
        $tomographyUri = null; // Si es 'new' o el nombre del archivo

        // --- Lógica de Manejo de Archivos ---
        if ($request->hasFile('tomography_file')) {
            $uploadedFile = $request->file('tomography_file');
            $originalExtension = $uploadedFile->getClientOriginalExtension();
            $baseFileName = time() . '_' . $patient->ci_patient; // Nombre base

            if ($originalExtension === 'zip') {
                // 1. Guardar el archivo ZIP original
                $zipFileName = $baseFileName . '.' . $originalExtension;
                $uploadedFile->storeAs('public/tomographies/zips', $zipFileName);
                
                $zipPath = storage_path('app/public/tomographies/zips/' . $zipFileName);
                // La carpeta de extracción lleva el nombre base para ser fácil de referenciar
                $extractFolderName = $baseFileName; 
                $extractPath = storage_path('app/public/tomographies/raw_data/' . $extractFolderName);
                
                // Asegurar que la carpeta de destino exista
                File::makeDirectory($extractPath, 0755, true, true);
        
                $zip = new ZipArchive;
                if ($zip->open($zipPath) === true) {
                    // 2. Extraer el contenido (solo apertura, no conversión)
                    $zip->extractTo($extractPath);
                    $zip->close();
                    
                    // La URI de DICOM/Data será la ruta de la carpeta extraída
                    $imageDicomUri = 'tomographies/raw_data/' . $extractFolderName;
                    $tomographyUri = 'new'; // Indicador de que es un nuevo estudio ZIP
                    
                } else {
                    return redirect()->route('tomography.create')->with('error', 'No se pudo abrir el archivo ZIP.');
                }
            } else {
                // Es un archivo individual (ej. .dcm, .jpg)
                $individualFileName = $baseFileName . '.' . $originalExtension;
                $uploadedFile->storeAs('public/tomographies/individual', $individualFileName);
                
                // Las dos URIs apuntan al mismo archivo
                $imageDicomUri = 'tomographies/individual/' . $individualFileName;
                $tomographyUri = 'tomographies/individual/' . $individualFileName;
            }
        }
        // --- Fin Lógica de Manejo de Archivos ---

        // 3. Crear el registro en la base de datos
        $tomography = new Tomography();
        $tomography->name_patient = $patient->name_patient;
        $tomography->ci_patient = $patient->ci_patient;
        $tomography->tomography_id = $request->tomography_id;
        $tomography->tomography_date = $request->tomography_date;
        $tomography->tomography_type = $request->tomography_type;
        $tomography->tomography_dicom_uri = $imageDicomUri; // Ruta de la carpeta o archivo
        $tomography->tomography_uri = $tomographyUri; // 'new' o ruta del archivo

        $tomography->tomography_doctor = $request->tomography_doctor;
        $tomography->tomography_charge = $request->tomography_charge;
        $tomography->save();
        
        // Redireccionar al índice en lugar de a tomography.convert
        return redirect()->route('tomography.index')->with('success','Tomografía creada y archivos almacenados.');
    }
    
    public function showSelectedImage($tomographyId, $image){
        $tomography = Tomography::findOrFail($tomographyId);
        // La ruta a las imágenes ahora debe ser dinámica y buscar dentro de la carpeta del ZIP extraído.
        // Asumiendo que las imágenes ya están en 'raw_data/{nombre_de_la_carpeta}/{imagen}'
        $imagePath = storage_path("app/public/tomographies/raw_data/{$tomography->tomography_dicom_uri}/{$image}"); 
        
        // NOTA: Esta ruta puede necesitar ser ajustada dependiendo de cómo se cargan las imágenes
        // en la vista 'tomography.show_image'.

        if (!file_exists($imagePath)) {
            abort(404, 'Imagen no encontrada');
        }
        return view('tomography.mostrar', compact('tomographyId', 'image', 'tomography'));
    }
    public function report(Tomography $tomography):View{
        return view('tomography.report', compact('tomography'));
    }
    public function superposicion($id){
        $tomography = Tomography::findOrFail($id);
        
        // Si el estudio es un ZIP, tomography_dicom_uri contiene la ruta de la carpeta de datos
        $folderPath = storage_path('app/public/' . $tomography->tomography_dicom_uri);

        $images = [];
        if (is_dir($folderPath)) {
            $files = scandir($folderPath);
            foreach ($files as $file) {
                // Se buscan todos los archivos, ya que no sabemos qué extensión tienen los DICOM dentro
                // Puedes ajustar esta regex si solo quieres archivos específicos (ej: .dcm)
                if ($file !== '.' && $file !== '..') {
                    $images[] = asset('storage/' . $tomography->tomography_dicom_uri . '/' . $file);
                }
            }
        }
        return view('tomography.superposicion', compact('tomography', 'images'));
    }
    public function search(Request $request){
        $search = $request->input('search');
        $tomographies = Tomography::where('name_patient', 'LIKE', '%' . $search . '%')
                ->orWhere('tomography_id', 'LIKE', '%' . $search . '%')
                ->orWhere('ci_patient', 'LIKE', '%' . $search . '%')->get();
        return view('tomography.search', compact('tomographies'));
    }
    public function destroy(Tomography $tomography){
        $tomography->delete();
        return redirect()->route('tomography.index')->with('danger','Estudio eliminado');
    }
}