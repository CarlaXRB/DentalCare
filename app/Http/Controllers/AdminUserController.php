<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $users = User::with('clinic')->simplePaginate(10);
        return view('admin.users', compact('users'));
    }

    public function create(){
        $clinics = Clinic::all();
        return view('admin.create', compact('clinics'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'rol' => 'required|string|in:user,doctor,recepcionist,radiology,admin',
            'clinic_id' => 'nullable|exists:clinics,id',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['rol'],
            'clinic_id' => $validated['clinic_id'] ?? null,
        ]);

        return redirect()->route('admin.users')->with('success', 'Usuario creado exitosamente');
    }

    public function destroy(User $user){
        $user->delete();
        return redirect()->route('admin.users')->with('danger','Usuario eliminado');
    }
}
