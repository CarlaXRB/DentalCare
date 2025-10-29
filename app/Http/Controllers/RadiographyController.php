<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\RadiographyRequest;
use App\Services\ImageFilterService;
use App\Models\Radiography;
use App\Models\Patient;

class RadiographyController extends Controller
{
    protected $imageFilterService;

    public function __construct(ImageFilterService $imageFilterService){
        $this->imageFilterService = $imageFilterService;
    }
    public function index():View{
        $radiographies = Radiography::get();
        return view('radiography.index', compact('radiographies'));
    }
    public function new():View{
        return view('radiography.newradiography');
    }
    public function create():View{
        $patients = Patient::all();
        return view('radiography.create', compact('patients'));
    }
    public function edit(Radiography $radiography):View{
        $patients = Patient::all();
        return view('radiography.edit', compact('radiography','patients'));
    }
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
    public function show(Radiography $radiography):View{
        return view('radiography.show', compact('radiography'));
    }
    public function tool(Radiography $radiography):View{
        return view('radiography.tool', compact('radiography'));
    }
    public function measurements(Radiography $radiography):View{
        return view('radiography.measurements', compact('radiography'));
    }
     public function store(RadiographyRequest $request):RedirectResponse{
        $patient = Patient::findOrFail($request->patient_id);
        $radiographyFile = $request->file('radiography_file');
        $fileName = time() . '_' . $patient->ci_patient . '.' . $radiographyFile->getClientOriginalExtension();
        $filePath = $radiographyFile->storeAs('public/radiographies', $fileName); 
        $radiography=new Radiography;
        $radiography->name_patient=$patient->name_patient;
        $radiography->ci_patient=$patient->ci_patient;
        $radiography->radiography_id=$request->radiography_id;
        $radiography->radiography_date=$request->radiography_date;
        $radiography->radiography_type=$request->radiography_type;
        $radiography->radiography_uri=$fileName; 
        $radiography->radiography_doctor=$request->radiography_doctor;
        $radiography->radiography_charge=$request->radiography_charge;
        $radiography->save();

        return redirect()->route('radiography.index')->with('success','Radiografia creada');
    }
    public function report(Radiography $radiography):View{
        return view('radiography.report', compact('radiography'));
    }
    public function search(Request $request){
        $search = $request->input('search');
        $radiographies = Radiography::where('name_patient', 'LIKE', '%' . $search . '%')
                ->orWhere('radiography_id', 'LIKE', '%' . $search . '%')
                ->orWhere('ci_patient', 'LIKE', '%' . $search . '%')->get();
        return view('radiography.search', compact('radiographies'));
    }
    public function destroy(Radiography $radiography){
        $radiography->delete();
        return redirect()->route('radiography.index')->with('danger','Estudio eliminado');
    }
    public function files(){
        return view('admin.files');
    }
}
