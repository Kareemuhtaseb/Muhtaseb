<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $income = Income::with(['category'])->latest()->paginate(15);
        return response()->json($income);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'payment_method' => 'required|string|max:100'
        ]);

        $income = Income::create($validated);
        return response()->json($income->load(['category']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Income $income): JsonResponse
    {
        return response()->json($income->load(['category']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Income $income): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'amount' => 'sometimes|numeric|min:0',
            'description' => 'sometimes|string|max:255',
            'date' => 'sometimes|date',
            'payment_method' => 'sometimes|string|max:100'
        ]);

        $income->update($validated);
        return response()->json($income->load(['category']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Income $income): JsonResponse
    {
        $income->delete();
        return response()->json(null, 204);
    }

    /**
     * Get income by month
     */
    public function byMonth(int $year, int $month): JsonResponse
    {
        $income = Income::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with(['category'])
            ->get();

        $total = $income->sum('amount');

        return response()->json([
            'income' => $income,
            'total' => $total,
            'month' => $month,
            'year' => $year
        ]);
    }
}
