<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('role:superadmin'); // Middleware que solo permite superadmin
    }

    public function index(){
        $clinics = Clinic::paginate(10);
        return view('clinics.index', compact('clinics'));
    }

    public function create(){
        return view('clinics.create');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|max:1024',
            'rooms' => 'required|integer|min:1', // Número de consultorios
        ]);

        Clinic::create($validated);

        return redirect()->route('clinics.index')->with('success', 'Clínica creada exitosamente');
    }

    public function edit(Clinic $clinic){
        return view('clinics.edit', compact('clinic'));
    }

    public function update(Request $request, Clinic $clinic){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|max:1024',
            'rooms' => 'required|integer|min:1',
        ]);

        $clinic->update($validated);

        return redirect()->route('clinics.index')->with('success', 'Clínica actualizada exitosamente');
    }

    public function destroy(Clinic $clinic){
        $clinic->delete();
        return redirect()->route('clinics.index')->with('danger', 'Clínica eliminada');
    }
}
