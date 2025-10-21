<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\Event;
use App\Models\Report;
use App\Models\Radiography;
use App\Models\Treatment;
use App\Models\Tomography;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index(){
        $users=User::simplePaginate(10);
        return view('admin.users', compact('users'));
    }
    public function create(){
        return view('admin.create');
    }
    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'rol' => 'required|string|in:user,doctor,recepcionist,radiology,admin',
        ]);
        $role = $request->input('rol');
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $role,
        ]);
        return redirect()->route('admin.users')->with('success', 'Usuario creado exitosamente');
    }
    public function destroy(User $user){
        $user->delete();
        return redirect()->route('admin.users', ['user' => $user->id])->with('danger','Usuario eliminado');
    }

    public function data(){
        $totalUsers = User::count();
        $totalPatients = Patient::count();
        $totalEvents = Event::count();
        $totalRadiographies = Radiography::count();
        $totalTomographies = Tomography::count();
        $totalReports = Report::count();
        $totalTreatments = Treatment::count();

        $monthlyPatients = Patient::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', 2025)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyEvents = Event::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', 2025)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyRadiographies = Radiography::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', 2025)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyTomographies = Tomography::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', 2025)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyReports = Report::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', 2025)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyUsers = User::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', 2025)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
        
        $monthlyTreatments = Treatment::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', 2025)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $months = range(1, 12);
        $monthlyPatients = array_map(fn($m) => $monthlyPatients[$m] ?? 0, $months);
        $monthlyEvents = array_map(fn($m) => $monthlyEvents[$m] ?? 0, $months);
        $monthlyRadiographies = array_map(fn($m) => $monthlyRadiographies[$m] ?? 0, $months);
        $monthlyTomographies = array_map(fn($m) => $monthlyTomographies[$m] ?? 0, $months);
        $monthlyReports = array_map(fn($m) => $monthlyReports[$m] ?? 0, $months);
        $monthlyUsers = array_map(fn($m) => $monthlyUsers[$m] ?? 0, $months);
        $monthlyTreatments = array_map(fn($m) => $monthlyTreatments[$m] ?? 0, $months);
        $monthNames = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
                $monthlyData = [];
        foreach ($months as $i) {
            $monthlyData[] = [
                'month' => $monthNames[$i],
                'patients' => $monthlyPatients[$i - 1],
                'radiographies' => $monthlyRadiographies[$i - 1],
                'tomographies' => $monthlyTomographies[$i - 1],
                'events' => $monthlyEvents[$i - 1],
                'reports' => $monthlyReports[$i - 1],
                'users' => $monthlyUsers[$i - 1],
                'treatments' => $monthlyTreatments[$i - 1],
            ];
        }

        return view('dashboard.data', compact(
            'totalUsers','totalPatients','totalEvents','totalRadiographies','totalTomographies','totalReports','totalTreatments','monthlyPatients','monthlyEvents','monthlyRadiographies','monthlyTomographies','monthlyReports','monthlyUsers','monthlyData', 'monthlyTreatments'
        ));
    }   
}
