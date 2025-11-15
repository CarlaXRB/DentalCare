<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Listado de usuarios
    public function index()
    {
        if (Auth::user()->role === 'superadmin') {
            $users = User::with('clinic')->simplePaginate(10);
        } else {
            $users = User::with('clinic')
                ->where('clinic_id', Auth::user()->clinic_id)
                ->simplePaginate(10);
        }

        return view('admin.users', compact('users'));
    }

    // Formulario de creación
    public function create()
    {
        if (Auth::user()->role === 'superadmin') {
            $clinics = Clinic::all();
            return view('admin.create', compact('clinics'));
        }

        // Para admin normal, no mostrar select de clínica
        return view('admin.create');
    }

    // Almacenar nuevo usuario
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'ci' => 'required|string|max:20|unique:users,ci',
            'rol' => 'required|string|in:user,doctor,recepcionist,radiology,admin',
            'clinic_id' => 'nullable|exists:clinics,id',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'ci' => $validated['ci'],
            // La contraseña se asigna automáticamente igual al CI
            'password' => Hash::make($validated['ci']),
            'role' => $validated['rol'],
            'clinic_id' => Auth::user()->role === 'superadmin'
                ? ($validated['clinic_id'] ?? null)
                : Auth::user()->clinic_id,
            'created_by' => Auth::id(),
            'edit_by' => Auth::id(),
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'Usuario creado exitosamente. La contraseña es el CI del usuario.');
    }

    // Formulario de edición
    public function edit(User $user)
    {
        if (Auth::user()->role !== 'superadmin' && $user->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'No tienes permiso para editar este usuario');
        }

        if (Auth::user()->role === 'superadmin') {
            $clinics = Clinic::all();
            return view('admin.edit', compact('user', 'clinics'));
        }

        // Para admin normal, no mostrar select de clínica
        return view('admin.edit', compact('user'));
    }

    // Actualizar usuario
    public function update(Request $request, User $user)
    {
        if (Auth::user()->role !== 'superadmin' && $user->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'No tienes permiso para actualizar este usuario');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|string|email|max:255|unique:users,email,{$user->id}",
            'ci' => "required|string|max:20|unique:users,ci,{$user->id}",
            'rol' => 'required|string|in:user,doctor,recepcionist,radiology,admin',
            'clinic_id' => 'nullable|exists:clinics,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->ci = $validated['ci'];
        $user->role = $validated['rol'];
        $user->clinic_id = Auth::user()->role === 'superadmin'
            ? ($validated['clinic_id'] ?? null)
            : Auth::user()->clinic_id;
        $user->edit_by = Auth::id();

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users')
            ->with('success', 'Usuario actualizado correctamente');
    }
    public function show(User $user)
    {
        // Validación de permisos: los admins solo pueden ver usuarios de su clínica
        if (Auth::user()->role !== 'superadmin' && $user->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'No tienes permiso para ver este usuario');
        }

        return view('admin.show', compact('user'));
    }


    // Eliminar usuario
    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'superadmin' && $user->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'No tienes permiso para eliminar este usuario');
        }

        $user->delete();

        return redirect()->route('admin.users')
            ->with('danger', 'Usuario eliminado');
    }

    // Búsqueda de usuarios
    public function search(Request $request)
    {
        $query = $request->input('query');

        $users = User::with('clinic')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('ci', 'like', "%{$query}%");
            });

        if (Auth::user()->role !== 'superadmin') {
            $users->where('clinic_id', Auth::user()->clinic_id);
        }

        $users = $users->simplePaginate(10)->appends(['query' => $query]);

        return view('admin.users', compact('users', 'query'));
    }
}
