<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\RadiographyRequest;
use App\Services\ImageFilterService;
use App\Models\Radiography;
use App\Models\Patient;
// Se eliminan las importaciones de Route y Storage, ya que no se usan directamente en el controlador.
// use Illuminate\Support\Facades\Route; 
// use Illuminate\Support\Facades\Storage; 

class RadiographyController extends Controller
{
    protected $imageFilterService;

    public function __construct(ImageFilterService $imageFilterService){
        $this->imageFilterService = $imageFilterService;
    }

    /**
     * Muestra una lista de todas las radiografías.
     */
    public function index():View{
        $radiographies = Radiography::get();
        return view('radiography.index', compact('radiographies'));
    }
    
    // Función 'new' no implementada completamente, redirige a una vista placeholder.
    public function new():View{
        return view('radiography.newradiography');
    }

    /**
     * Muestra el formulario para crear una nueva radiografía.
     */
    public function create():View{
        $patients = Patient::all();
        return view('radiography.create', compact('patients'));
    }

    /**
     * Muestra el formulario para editar una radiografía existente.
     */
    public function edit(Radiography $radiography):View{
        $patients = Patient::all();
        return view('radiography.edit', compact('radiography','patients'));
    }

    /**
     * Actualiza la información de una radiografía existente.
     */
    public function update(RadiographyRequest $request, Radiography $radiography):RedirectResponse{
        $patient = Patient::findOrFail($request->patient_id);
        $radiography->name_patient=$patient->name_patient;
        $radiography->ci_patient=$patient->ci_patient;
        
        $radiography->update([
            'radiography_id' => $request->input('radiography_id'),
            'radiography_date' => $request->input('radiography_date'),
            'radiography_type' => $request->input('radiography_type'),
            'radiography_doctor' => $request->input('radiography_doctor'),
            'radiography_charge' => $request->input('radiography_charge'),
        ]);
        
        return redirect()->route('radiography.index')->with('success','Información actualizada');
    }

    /**
     * Muestra los detalles de una radiografía, incluyendo la imagen.
     */
    public function show(Radiography $radiography):View{
        return view('radiography.show', compact('radiography'));
    }

    /**
     * Muestra la herramienta de procesamiento de imagen.
     */
    public function tool(Radiography $radiography):View{
        return view('radiography.tool', compact('radiography'));
    }

    /**
     * Muestra la vista para realizar mediciones en la imagen.
     */
    public function measurements(Radiography $radiography):View{
        return view('radiography.measurements', compact('radiography'));
    }

    /**
     * Almacena una nueva radiografía y su archivo asociado.
     * * NOTA: La lógica de la ruta para servir el archivo (radiography.file)
     * ha sido movida a routes/web.php, lo cual es correcto.
     */
    public function store(RadiographyRequest $request):RedirectResponse{
        $patient = Patient::findOrFail($request->patient_id);
        $radiographyFile = $request->file('radiography_file');
        
        // 1. Crear nombre único del archivo
        $fileName = time() . '_' . $patient->ci_patient . '.' . $radiographyFile->getClientOriginalExtension();
        
        // 2. Guardar el archivo en storage/app/public/radiographies/
        $filePath = $radiographyFile->storeAs('public/radiographies', $fileName); 
        
        // 3. Crear el registro en la base de datos
        $radiography=new Radiography;
        $radiography->name_patient=$patient->name_patient;
        $radiography->ci_patient=$patient->ci_patient;
        $radiography->radiography_id=$request->radiography_id;
        $radiography->radiography_date=$request->radiography_date;
        $radiography->radiography_type=$request->radiography_type;
        $radiography->radiography_uri=$fileName; // Se guarda solo el nombre del archivo
        $radiography->radiography_doctor=$request->radiography_doctor;
        $radiography->radiography_charge=$request->radiography_charge;
        $radiography->save();

        return redirect()->route('radiography.index')->with('success','Radiografia creada');
    }

    /**
     * Muestra la vista para generar el reporte.
     */
    public function report(Radiography $radiography):View{
        return view('radiography.report', compact('radiography'));
    }

    /**
     * Busca radiografías por nombre, ID o CI del paciente.
     */
    public function search(Request $request){
        $search = $request->input('search');
        $radiographies = Radiography::where('name_patient', 'LIKE', '%' . $search . '%')
                ->orWhere('radiography_id', 'LIKE', '%' . $search . '%')
                ->orWhere('ci_patient', 'LIKE', '%' . $search . '%')->get();
        return view('radiography.search', compact('radiographies'));
    }

    /**
     * Elimina una radiografía.
     */
    public function destroy(Radiography $radiography){
        $radiography->delete();
        return redirect()->route('radiography.index')->with('danger','Estudio eliminado');
    }

    /**
     * Muestra una vista para administración de archivos (Placeholder).
     */
    public function files(){
        return view('admin.files');
    }
}