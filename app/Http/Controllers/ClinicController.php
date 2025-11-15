<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ClinicController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
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
            'rooms_count' => 'required|integer|min:1',
        ]);

        $storagePath = storage_path('app/public/logos');
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0775, true);
        }

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $filename = time() . '_' . $logo->getClientOriginalName();
            $logo->move($storagePath, $filename);
            $validated['logo'] = 'storage/logos/' . $filename;
        }

        Clinic::create($validated);

        return redirect()->route('clinics.index')->with('success', 'Clínica creada exitosamente');
    }

    public function show(Clinic $clinic){
        // Retornamos la vista con la clínica específica
        return view('clinics.show', compact('clinic'));
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
            'rooms_count' => 'required|integer|min:1',
        ]);

        $storagePath = storage_path('app/public/logos');
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0775, true);
        }

        if ($request->hasFile('logo')) {
            if ($clinic->logo) {
                $oldPath = str_replace('storage/', storage_path('app/public/') , $clinic->logo);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $logo = $request->file('logo');
            $filename = time() . '_' . $logo->getClientOriginalName();
            $logo->move($storagePath, $filename);
            $validated['logo'] = 'storage/logos/' . $filename;
        }

        $clinic->update($validated);

        return redirect()->route('clinics.index')->with('success', 'Clínica actualizada exitosamente');
    }

    public function destroy(Clinic $clinic){
        if ($clinic->logo) {
            $oldPath = str_replace('storage/', storage_path('app/public/') , $clinic->logo);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }
        $clinic->delete();
        return redirect()->route('clinics.index')->with('danger', 'Clínica eliminada');
    }

    public function search(Request $request) {
        $search = $request->input('search');
        $clinics = Clinic::where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('phone', 'LIKE', '%' . $search . '%')->get();
        return view('clinics.search', compact('clinics'));
    }
}
