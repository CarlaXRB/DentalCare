<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\PatientRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\Patient;
use App\Models\User;

class PatientController extends Controller
{
    public function index():View{
        $patients=Patient::simplePaginate(10);
        return view('patient.index', compact('patients'));
    }
    public function create():View{
        return view('patient.create');
    }
    public function store(PatientRequest $request):RedirectResponse{
        $patient = Patient::create($request->all());
        return redirect()->route('patient.index')->with('success','Paciente creado');
    }
    public function show(Patient $patient):View{
        return view('patient.show', compact('patient'));
    }
    public function edit(Patient $patient):View{
        return view('patient.edit', compact('patient'));
    }
    public function update(PatientRequest $request, Patient $patient):RedirectResponse{
        $patient->update($request->all());
        return redirect()->route('patient.index')->with('success','Información actualizada');
    }
    public function destroy(Patient $patient):RedirectResponse{
        $patient->delete();
        return redirect()->route('patient.index')->with('danger','Registro borrado');
    }
    public function search(Request $request) {
        $search = $request->input('search');
        $patients = Patient::where('name_patient', 'LIKE', '%' . $search . '%')
                ->orWhere('ci_patient', 'LIKE', '%' . $search . '%')->get();
        return view('patient.search', compact('patients'));
    }
}
