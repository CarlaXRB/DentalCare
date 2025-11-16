<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\PatientRequest;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if ($user->role === 'superadmin') {
            // Superadmin ve todos
            $patients = Patient::orderBy('name_patient', 'ASC')->paginate(10);
        } else {
            // Administradores y otros roles ven solo su clínica
            $patients = Patient::where('clinic_id', $user->clinic_id)
                ->orderBy('name_patient', 'ASC')
                ->paginate(10);
        }

        return view('patient.index', compact('patients'));
    }

    public function create(): View
    {
        return view('patient.create');
    }

    public function store(PatientRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['clinic_id'] = Auth::user()->clinic_id;
        $data['created_by'] = Auth::id();

        Patient::create($data);

        return redirect()->route('patient.index')
            ->with('success', 'Paciente creado correctamente');
    }

    public function show(Patient $patient): View
    {
        return view('patient.show', compact('patient'));
    }

    public function edit(Patient $patient): View
    {
        return view('patient.edit', compact('patient'));
    }

    public function update(PatientRequest $request, Patient $patient): RedirectResponse
    {
        $data = $request->validated();
        $data['edit_by'] = Auth::id();

        $patient->update($data);

        return redirect()->route('patient.index')
            ->with('success', 'Información actualizada correctamente');
    }

    public function destroy(Patient $patient): RedirectResponse
    {
        $patient->delete();

        return redirect()->route('patient.index')
            ->with('danger', 'Registro borrado');
    }

    public function search(Request $request): View
    {
        $user = Auth::user();
        $search = $request->input('search');

        $query = Patient::query();

        // Filtro de texto
        $query->where(function($q) use ($search) {
            $q->where('name_patient', 'LIKE', "%{$search}%")
              ->orWhere('ci_patient', 'LIKE', "%{$search}%");
        });

        // Filtro por clínica excepto para superadmin
        if ($user->role !== 'superadmin') {
            $query->where('clinic_id', $user->clinic_id);
        }

        $patients = $query->paginate(10);

        return view('patient.index', compact('patients'));
    }
}
