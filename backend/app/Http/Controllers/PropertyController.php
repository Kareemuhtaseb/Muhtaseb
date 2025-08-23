<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Unit;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class PropertyController extends Controller
{
    /**
     * Display a listing of properties.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Property::query();

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Load relationships
        $query->with(['units', 'contracts', 'income', 'expenses']);

        $properties = $query->orderBy('created_at', 'desc')
                           ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $properties
        ]);
    }

    /**
     * Store a newly created property.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'property_type' => 'required|in:residential,commercial,industrial,mixed',
            'status' => 'required|in:active,inactive,sold,under_construction',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'current_value' => 'nullable|numeric|min:0',
            'total_area' => 'nullable|numeric|min:0',
            'units_count' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $property = Property::create($validated);
        $property->load(['units', 'contracts']);

        return response()->json([
            'success' => true,
            'message' => 'Property created successfully',
            'data' => $property
        ], 201);
    }

    /**
     * Display the specified property.
     */
    public function show(Property $property): JsonResponse
    {
        $property->load(['units', 'contracts', 'income', 'expenses', 'owners']);

        // Add financial analysis
        $financialReport = $property->getFinancialReport();
        $varianceReport = $property->getIncomeVarianceReport();

        return response()->json([
            'success' => true,
            'data' => array_merge($property->toArray(), [
                'financial_report' => $financialReport,
                'variance_analysis' => $varianceReport
            ])
        ]);
    }

    /**
     * Update the specified property.
     */
    public function update(Request $request, Property $property): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:500',
            'property_type' => 'sometimes|in:residential,commercial,industrial,mixed',
            'status' => 'sometimes|in:active,inactive,sold,under_construction',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'current_value' => 'nullable|numeric|min:0',
            'total_area' => 'nullable|numeric|min:0',
            'units_count' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $property->update($validated);
        $property->load(['units', 'contracts']);

        return response()->json([
            'success' => true,
            'message' => 'Property updated successfully',
            'data' => $property
        ]);
    }

    /**
     * Remove the specified property.
     */
    public function destroy(Property $property): JsonResponse
    {
        $property->delete();

        return response()->json([
            'success' => true,
            'message' => 'Property deleted successfully'
        ]);
    }

    /**
     * Get property financial report.
     */
    public function financialReport(Request $request, Property $property): JsonResponse
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->start_date) : now()->startOfYear();
        $endDate = $request->get('end_date') ? Carbon::parse($request->end_date) : now()->endOfYear();

        $report = $property->getFinancialReport($startDate, $endDate);

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Get property units.
     */
    public function units(Property $property): JsonResponse
    {
        $units = $property->units()->with(['contracts', 'income', 'expenses'])->get();

        return response()->json([
            'success' => true,
            'data' => $units
        ]);
    }

    /**
     * Get property income.
     */
    public function income(Request $request, Property $property): JsonResponse
    {
        $query = $property->income()->with(['category', 'contract']);

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
     * Get property expenses.
     */
    public function expenses(Request $request, Property $property): JsonResponse
    {
        $query = $property->expenses()->with(['category']);

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
     * Get property contracts.
     */
    public function contracts(Request $request, Property $property): JsonResponse
    {
        $query = $property->contracts()->with(['category', 'unit']);

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
     * Get property statistics.
     */
    public function statistics(Property $property): JsonResponse
    {
        $stats = [
            'total_units' => $property->units()->count(),
            'occupied_units' => $property->units()->where('status', 'occupied')->count(),
            'available_units' => $property->units()->where('status', 'available')->count(),
            'maintenance_units' => $property->units()->where('status', 'maintenance')->count(),
            'occupancy_rate' => $property->occupancy_rate,
            'active_contracts' => $property->contracts()->active()->count(),
            'expired_contracts' => $property->contracts()->expired()->count(),
            'total_income' => $property->total_income,
            'total_expenses' => $property->total_expenses,
            'net_income' => $property->net_income,
            'expected_income' => $property->expected_income,
            'actual_income' => $property->actual_income,
            'variance' => $property->variance,
            'variance_percentage' => $property->variance_percentage,
            'roi' => $property->roi
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get all properties financial summary.
     */
    public function financialSummary(Request $request): JsonResponse
    {
        $period = $request->get('period', 'all');
        $startDate = null;
        $endDate = null;

        switch ($period) {
            case 'current_month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'current_year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
            case 'custom':
                $startDate = Carbon::parse($request->start_date);
                $endDate = Carbon::parse($request->end_date);
                break;
        }

        $properties = Property::with(['units', 'contracts', 'income', 'expenses'])->get();
        $summary = [];

        foreach ($properties as $property) {
            $report = $property->getFinancialReport($startDate, $endDate);
            $summary[] = $report;
        }

        $totalExpected = collect($summary)->sum('income.expected');
        $totalActual = collect($summary)->sum('income.actual');
        $totalExpenses = collect($summary)->sum('expenses');
        $totalNetIncome = collect($summary)->sum('net_income');

        $overallSummary = [
            'total_properties' => count($properties),
            'active_properties' => $properties->where('status', 'active')->count(),
            'total_expected_income' => $totalExpected,
            'total_actual_income' => $totalActual,
            'total_expenses' => $totalExpenses,
            'total_net_income' => $totalNetIncome,
            'overall_variance' => $totalExpected - $totalActual,
            'overall_variance_percentage' => $totalExpected > 0 ? (($totalExpected - $totalActual) / $totalExpected) * 100 : 0,
            'period' => $period,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'properties' => $summary
        ];

        return response()->json([
            'success' => true,
            'data' => $overallSummary
        ]);
    }
}
