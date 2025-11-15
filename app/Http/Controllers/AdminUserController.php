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
    public function create()
    {
        if (Auth::user()->role === 'superadmin') {
            $clinics = Clinic::all();
            return view('admin.create', compact('clinics'));
        } else {
            $clinics = Clinic::where('id', Auth::user()->clinic_id)->get();
        }
        return view('admin.create');
    }
    public function store(Request $request)
    {
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
            'clinic_id' => Auth::user()->role === 'superadmin'
                ? ($validated['clinic_id'] ?? null)
                : Auth::user()->clinic_id,
            'created_by' => Auth::id(),
            'edit_by' => Auth::id(),
        ]);
        return redirect()->route('admin.users')->with('success', 'Usuario creado exitosamente');
    }
    public function edit(User $user)
    {
        if (Auth::user()->role !== 'superadmin' && $user->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'No tienes permiso para editar este usuario');
        }
        if (Auth::user()->role === 'superadmin') {
            $clinics = Clinic::all();
        } else {
            $clinics = Clinic::where('id', Auth::user()->clinic_id)->get();
        }
        return view('admin.edit', compact('user', 'clinics'));
    }

    public function update(Request $request, User $user)
    {
        if (Auth::user()->role !== 'superadmin' && $user->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'No tienes permiso para actualizar este usuario');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|string|email|max:255|unique:users,email,{$user->id}",
            'rol' => 'required|string|in:user,doctor,recepcionist,radiology,admin',
            'clinic_id' => 'nullable|exists:clinics,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['rol'];
        $user->clinic_id = Auth::user()->role === 'superadmin'
            ? ($validated['clinic_id'] ?? null)
            : Auth::user()->clinic_id;
        $user->edit_by = Auth::id();
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();
        return redirect()->route('admin.users')->with('success', 'Usuario actualizado correctamente');
    }
    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'superadmin' && $user->clinic_id !== Auth::user()->clinic_id) {
            abort(403, 'No tienes permiso para eliminar este usuario');
        }
        $user->delete();
        return redirect()->route('admin.users')->with('danger', 'Usuario eliminado');
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        $users = User::with('clinic')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            });
        if (Auth::user()->role !== 'superadmin') {
            $users->where('clinic_id', Auth::user()->clinic_id);
        }
        $users = $users->simplePaginate(10)->appends(['query' => $query]);
        return view('admin.users', compact('users', 'query'));
    }
}
