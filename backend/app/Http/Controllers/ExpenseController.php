<?php
namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        return Expense::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category' => 'required',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'notes' => 'nullable'
        ]);
        return Expense::create($data);
    }

    public function show(Expense $expense)
    {
        return $expense;
    }

    public function update(Request $request, Expense $expense)
    {
        $data = $request->validate([
            'category' => 'sometimes',
            'date' => 'sometimes|date',
            'amount' => 'sometimes|numeric',
            'notes' => 'nullable'
        ]);
        $expense->update($data);
        return $expense;
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return response()->noContent();
    }
}
