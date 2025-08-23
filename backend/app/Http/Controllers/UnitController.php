<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UnitController extends Controller
{
    /**
     * Display a listing of units.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Unit::query();

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('unit_type')) {
            $query->where('unit_type', $request->unit_type);
        }

        if ($request->has('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        if ($request->has('unit_number')) {
            $query->where('unit_number', 'like', '%' . $request->unit_number . '%');
        }

        // Load relationships
        $query->with(['property', 'contracts', 'income', 'expenses']);

        $units = $query->orderBy('created_at', 'desc')
                      ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $units
        ]);
    }

    /**
     * Store a newly created unit.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_number' => 'required|string|max:100',
            'unit_type' => 'required|string|max:100',
            'status' => 'required|in:available,occupied,maintenance,reserved',
            'area' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'monthly_rent' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'notes' => 'nullable|string'
        ]);

        $unit = Unit::create($validated);
        $unit->load(['property', 'contracts']);

        return response()->json([
            'success' => true,
            'message' => 'Unit created successfully',
            'data' => $unit
        ], 201);
    }

    /**
     * Display the specified unit.
     */
    public function show(Unit $unit): JsonResponse
    {
        $unit->load(['property', 'contracts', 'income', 'expenses']);

        // Add financial analysis
        $financialReport = $unit->getFinancialReport();

        return response()->json([
            'success' => true,
            'data' => array_merge($unit->toArray(), [
                'financial_report' => $financialReport
            ])
        ]);
    }

    /**
     * Update the specified unit.
     */
    public function update(Request $request, Unit $unit): JsonResponse
    {
        $validated = $request->validate([
            'property_id' => 'sometimes|exists:properties,id',
            'unit_number' => 'sometimes|string|max:100',
            'unit_type' => 'sometimes|string|max:100',
            'status' => 'sometimes|in:available,occupied,maintenance,reserved',
            'area' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'monthly_rent' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'notes' => 'nullable|string'
        ]);

        $unit->update($validated);
        $unit->load(['property', 'contracts']);

        return response()->json([
            'success' => true,
            'message' => 'Unit updated successfully',
            'data' => $unit
        ]);
    }

    /**
     * Remove the specified unit.
     */
    public function destroy(Unit $unit): JsonResponse
    {
        $unit->delete();

        return response()->json([
            'success' => true,
            'message' => 'Unit deleted successfully'
        ]);
    }

    /**
     * Get unit financial report.
     */
    public function financialReport(Request $request, Unit $unit): JsonResponse
    {
        $startDate = $request->get('start_date') ? \Carbon\Carbon::parse($request->start_date) : now()->startOfYear();
        $endDate = $request->get('end_date') ? \Carbon\Carbon::parse($request->end_date) : now()->endOfYear();

        $report = $unit->getFinancialReport($startDate, $endDate);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Get unit contracts.
     */
    public function contracts(Request $request, Unit $unit): JsonResponse
    {
        $query = $unit->contracts()->with(['category']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $contracts = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $contracts
        ]);
    }

    /**
     * Get unit income.
     */
    public function income(Request $request, Unit $unit): JsonResponse
    {
        $query = $unit->income()->with(['category', 'contract']);

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $income = $query->orderBy('date', 'desc')->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $income
        ]);
    }

    /**
     * Get unit expenses.
     */
    public function expenses(Request $request, Unit $unit): JsonResponse
    {
        $query = $unit->expenses()->with(['category']);

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $expenses = $query->orderBy('date', 'desc')->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $expenses
        ]);
    }

    /**
     * Get unit statistics.
     */
    public function statistics(Unit $unit): JsonResponse
    {
        $stats = [
            'total_income' => $unit->total_income,
            'total_expenses' => $unit->expenses()->sum('amount'),
            'net_income' => $unit->income()->sum('amount') - $unit->expenses()->sum('amount'),
            'expected_income' => $unit->expected_income,
            'actual_income' => $unit->actual_income,
            'variance' => $unit->variance,
            'variance_percentage' => $unit->variance_percentage,
            'active_contracts' => $unit->contracts()->active()->count(),
            'expired_contracts' => $unit->contracts()->expired()->count(),
            'status' => $unit->status,
            'monthly_rent' => $unit->monthly_rent
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
