<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Models\Budget;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class TreatmentController extends Controller
{

    public function index()
    {
        $treatments = Treatment::simplePaginate(10);
        return view('treatments.index', compact('treatments'));
    }

    public function create()
    {
        $budgets = Budget::all();
        return view('treatments.create', compact('budgets'));
    }

    /*public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'ci_patient' => 'required|numeric',
            'selected_budgets' => 'required|array',
            'quantity' => 'nullable|array',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|string|in:fixed,percentage',
            'details' => 'nullable|string|max:255',
        ]);

        $selectedBudgets = $request->input('selected_budgets', []);
        $quantities = $request->input('quantity', []);

        $totalAmount = 0;
        $budgetCodes = [];

        foreach ($selectedBudgets as $id) {
            $budget = Budget::find($id);
            if ($budget) {
                $quantity = isset($quantities[$id]) ? (int)$quantities[$id] : 1;
                $totalAmount += $budget->total_amount * $quantity;
                $budgetCodes[$id] = $quantity;
            }
        }

        $discountType = $request->input('discount_type', 'fixed');
        $discountValue = $request->input('discount', 0);

        $discount = ($discountType === 'percentage') ? ($discountValue / 100) * $totalAmount : $discountValue;
        $finalAmount = max($totalAmount - $discount, 0);

        $treatment = Treatment::create([
            'name' => $request->name,
            'ci_patient' => $request->ci_patient,
            'budget_codes' => json_encode($budgetCodes),
            'total_amount' => $totalAmount,
            'discount' => $discount,
            'amount' => $finalAmount,
            'details' => $request->details,
            'pdf_path' => null,
        ]);

        $budgets = Budget::whereIn('id', array_keys($budgetCodes))->get();

        $pdf = Pdf::loadView('treatments.pdf', [
            'treatment' => $treatment,
            'budgets' => $budgets,
            'author' => Auth::user()->name ?? 'Unknown User',
        ])->setPaper('a4', 'portrait');

        $fileName = 'treatment_' . $treatment->id . '.pdf';
        $storagePath = 'treatments/' . $fileName;
        $fullPath = public_path('storage/' . $storagePath);

        $pdf->save($fullPath);

        $treatment->update(['pdf_path' => 'storage/' . $storagePath]);

        return response()->download($fullPath, $fileName)->deleteFileAfterSend(false);
    }*/
        public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'ci_patient' => 'required|numeric',
            'selected_budgets' => 'required|array',
            'quantity' => 'nullable|array',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|string|in:fixed,percentage',
            'details' => 'nullable|string|max:255',
        ]);

        $selectedBudgets = $request->input('selected_budgets', []);
        $quantities = $request->input('quantity', []);

        $totalAmount = 0;
        $budgetCodes = [];

        foreach ($selectedBudgets as $id) {
            $budget = Budget::find($id);
            if ($budget) {
                $quantity = isset($quantities[$id]) ? (int)$quantities[$id] : 1;
                $totalAmount += $budget->total_amount * $quantity;
                $budgetCodes[$id] = $quantity;
            }
        }

        $discountType = $request->input('discount_type', 'fixed');
        $discountValue = $request->input('discount', 0);

        $discount = ($discountType === 'percentage') ? ($discountValue / 100) * $totalAmount : $discountValue;
        $finalAmount = max($totalAmount - $discount, 0);

        // 1. Crear el tratamiento sin el path final
        $treatment = Treatment::create([
            'name' => $request->name,
            'ci_patient' => $request->ci_patient,
            'budget_codes' => json_encode($budgetCodes),
            'total_amount' => $totalAmount,
            'discount' => $discount,
            'amount' => $finalAmount,
            'details' => $request->details,
            'pdf_path' => null, // Temporalmente nulo
        ]);

        $budgets = Budget::whereIn('id', array_keys($budgetCodes))->get();

        $pdf = Pdf::loadView('treatments.pdf', [
            'treatment' => $treatment,
            'budgets' => $budgets,
            'author' => Auth::user()->name ?? 'Unknown User',
        ])->setPaper('a4', 'portrait');

        $fileName = 'treatment_' . $treatment->id . '.pdf';
        $storageDir = 'treatments'; // Directorio dentro de storage/app/
        $storagePath = $storageDir . '/' . $fileName;
        
        // 2. Guardar el PDF usando el Storage Facade
        // Esto guarda en storage/app/treatments/treatment_X.pdf
        Storage::put($storagePath, $pdf->output());

        // 3. Actualizar el path en la base de datos (path relativo al disco 'local')
        $treatment->update(['pdf_path' => $storagePath]);

        // 4. Obtener la ruta completa para la descarga (storage/app/...)
        $fullPath = storage_path('app/' . $storagePath);

        // 5. Limpiar el buffer de salida (CRÍTICO para Dompdf en algunos servidores)
        if (ob_get_level()) {
            ob_end_clean();
        }

        // 6. Devolver la descarga (asegurando el Content-Type)
        // Usamos deleteFileAfterSend(false) porque el archivo está gestionado por Storage::put
        return response()->download($fullPath, $fileName, [
            'Content-Type' => 'application/pdf', 
        ])->deleteFileAfterSend(false);
    }

    public function show($id)
    {
        $treatment = Treatment::findOrFail($id);
        $budgetIds = array_keys(json_decode($treatment->budget_codes, true) ?? []);
        $budgets = Budget::whereIn('id', $budgetIds)->get();

        return view('treatments.show', compact('treatment', 'budgets'));
    }

    public function destroy($id)
    {
        $treatment = Treatment::findOrFail($id);
        if ($treatment->pdf_path && file_exists(public_path($treatment->pdf_path))) {
            unlink(public_path($treatment->pdf_path));
        }
        $treatment->delete();

        return redirect()->route('treatments.index')->with('success', 'Tratamiento eliminado con éxito.');
    }
    public function search(Request $request)
    {
        $search = $request->input('search');
        $treatments = Treatment::where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('ci_patient', 'LIKE', '%' . $search . '%')->get();
        return view('treatments.search', compact('treatments'));
    }
}
