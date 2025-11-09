<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;

class BudgetController extends Controller
{
    public function index(){
        $budgets = Budget::orderBy('id', 'desc')->paginate(10);
        return view('budgets.index', compact('budgets'));
    }

    public function create(){
        return view('budgets.create');
    }

    public function store(Request $request){
        $request->validate([
            'budget' => 'required|string|max:100',
            'procedure' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'total_amount' => 'required|numeric|min:0',
        ]);

        Budget::create($request->all());
        return redirect()->route('budgets.index')->with('success', 'Presupuesto creado exitosamente.');
    }

    public function show(Budget $budget){
        return view('budgets.show', compact('budget'));
    }

    public function edit(Budget $budget){
        return view('budgets.edit', compact('budget'));
    }

    public function update(Request $request, Budget $budget){
        $request->validate([
            'budget' => 'required|string|max:100',
            'procedure' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $budget->update($request->all());
        return redirect()->route('budgets.index')
            ->with('success', 'Presupuesto actualizado exitosamente.');
    }

    public function destroy(Budget $budget){
        $budget->delete();
        return redirect()->route('budgets.index')
            ->with('danger', 'Presupuesto eliminado exitosamente.');
    }
    public function search(Request $request) {
        $search = $request->input('search');
        $budgets = Budget::where('budget', 'LIKE', '%' . $search . '%')
                ->orWhere('procedure', 'LIKE', '%' . $search . '%')->get();
        return view('budgets.search', compact('budgets'));
    }
}
