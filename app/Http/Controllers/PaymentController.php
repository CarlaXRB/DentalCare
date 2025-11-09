<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Treatment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::simplePaginate(25);
        return view('payments.index', compact('payments'));
    }
    public function show($treatment_id)
    {
        $treatment = Treatment::findOrFail($treatment_id);
        $payments = $treatment->payments()->latest()->get();
        $paid = $payments->sum('amount');
        $remaining = $treatment->amount - $paid;
        return view('payments.show', compact('treatment', 'payments', 'paid', 'remaining'));
    }
    public function create($treatment_id)
    {
        $treatment = Treatment::findOrFail($treatment_id);
        $paid = $treatment->payments()->sum('amount');
        $remaining = $treatment->amount - $paid;
        return view('payments.create', compact('treatment', 'paid', 'remaining'));
    }
    public function store(Request $request, $treatment_id)
    {
        $treatment = Treatment::findOrFail($treatment_id);
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'method' => 'nullable|string|max:50',
            'notes'  => 'nullable|string|max:255',
        ]);
        $paid = $treatment->payments()->sum('amount');
        $remaining = $treatment->amount - $paid;
        if ($request->amount > $remaining) {
            return back()->with('error', 'El pago excede el monto restante.');
        }
        Payment::create([
            'treatment_id' => $treatment->id,
            'amount' => $request->amount,
            'method' => $request->method ?? 'Efectivo',
            'notes' => $request->notes,
        ]);
        return redirect()->route('payments.show', $treatment->id)
            ->with('success', 'Pago registrado correctamente.');
    }
    public function destroy($treatment_id, $id)
    {
        $payment = Payment::where('treatment_id', $treatment_id)->findOrFail($id);
        $payment->delete();
        return back()->with('danger', 'Pago eliminado correctamente.');
    }
    public function search(Request $request, $treatment_id = null)
    {
        $search = trim($request->input('search'));
        $query = Payment::query()->with('treatment');
        if ($treatment_id) {
            $query->where('treatment_id', $treatment_id);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('treatment', function ($t) use ($search) {
                    $t->where('name', 'like', "%{$search}%")
                        ->orWhere('ci_patient', 'like', "%{$search}%");
                })
                    ->orWhere('method', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }
        $payments = $query->latest()->paginate(20);
        return view('payments.search', compact('payments', 'search', 'treatment_id'));
    }
}
