<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContractController extends Controller
{
    /**
     * Display a listing of contracts.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Contract::query();

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('client_name')) {
            $query->where('client_name', 'like', '%' . $request->client_name . '%');
        }

        if ($request->has('expiring_soon')) {
            $query->expiringSoon($request->get('expiring_soon', 30));
        }

        // Load relationships
        $query->with(['category', 'income']);

        $contracts = $query->orderBy('created_at', 'desc')
                          ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $contracts
        ]);
    }

    /**
     * Store a newly created contract.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:20',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'monthly_amount' => 'required|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'payment_frequency' => 'required|in:monthly,quarterly,annually',
            'status' => 'required|in:active,completed,cancelled,expired',
            'category_id' => 'required|exists:categories,id',
            'notes' => 'nullable|string',
            'auto_renew' => 'boolean',
            'renewal_terms' => 'nullable|string'
        ]);

        $contract = Contract::create($validated);
        $contract->load(['category']);

        return response()->json([
            'success' => true,
            'message' => 'Contract created successfully',
            'data' => $contract
        ], 201);
    }

    /**
     * Display the specified contract.
     */
    public function show(Contract $contract): JsonResponse
    {
        $contract->load(['category', 'income', 'payments']);

        // Add variance analysis
        $varianceReport = $contract->getIncomeVarianceReport();

        return response()->json([
            'success' => true,
            'data' => array_merge($contract->toArray(), [
                'variance_analysis' => $varianceReport
            ])
        ]);
    }

    /**
     * Update the specified contract.
     */
    public function update(Request $request, Contract $contract): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'client_name' => 'sometimes|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:20',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'monthly_amount' => 'sometimes|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'payment_frequency' => 'sometimes|in:monthly,quarterly,annually',
            'status' => 'sometimes|in:active,completed,cancelled,expired',
            'category_id' => 'sometimes|exists:categories,id',
            'notes' => 'nullable|string',
            'auto_renew' => 'boolean',
            'renewal_terms' => 'nullable|string'
        ]);

        $contract->update($validated);
        $contract->load(['category']);

        return response()->json([
            'success' => true,
            'message' => 'Contract updated successfully',
            'data' => $contract
        ]);
    }

    /**
     * Remove the specified contract.
     */
    public function destroy(Contract $contract): JsonResponse
    {
        $contract->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contract deleted successfully'
        ]);
    }

    /**
     * Get income variance analysis for all contracts.
     */
    public function varianceAnalysis(Request $request): JsonResponse
    {
        $period = $request->get('period', 'all'); // all, current_month, current_year, custom
        $startDate = null;
        $endDate = null;

        // Determine date range
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

        $contracts = Contract::with(['category', 'income'])->get();
        $varianceReports = [];

        foreach ($contracts as $contract) {
            $expected = $period === 'all' ? $contract->expected_income : $contract->calculateExpectedIncomeForPeriod($startDate, $endDate);
            $actual = $period === 'all' ? $contract->actual_income : $contract->income()
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');

            $variance = $expected - $actual;
            $percentage = $expected > 0 ? ($variance / $expected) * 100 : 0;

            $varianceReports[] = [
                'contract_id' => $contract->id,
                'contract_number' => $contract->contract_number,
                'title' => $contract->title,
                'client_name' => $contract->client_name,
                'category' => $contract->category->name ?? 'N/A',
                'expected_income' => $expected,
                'actual_income' => $actual,
                'variance' => $variance,
                'variance_percentage' => $percentage,
                'status' => $variance > 0 ? 'underperforming' : ($variance < 0 ? 'overperforming' : 'on_target'),
                'contract_status' => $contract->status,
                'is_active' => $contract->is_active,
                'start_date' => $contract->start_date,
                'end_date' => $contract->end_date
            ];
        }

        // Calculate summary statistics
        $totalExpected = collect($varianceReports)->sum('expected_income');
        $totalActual = collect($varianceReports)->sum('actual_income');
        $totalVariance = $totalExpected - $totalActual;
        $totalPercentage = $totalExpected > 0 ? ($totalVariance / $totalExpected) * 100 : 0;

        $summary = [
            'total_contracts' => count($varianceReports),
            'active_contracts' => collect($varianceReports)->where('is_active', true)->count(),
            'total_expected_income' => $totalExpected,
            'total_actual_income' => $totalActual,
            'total_variance' => $totalVariance,
            'total_variance_percentage' => $totalPercentage,
            'overall_status' => $totalVariance > 0 ? 'underperforming' : ($totalVariance < 0 ? 'overperforming' : 'on_target'),
            'period' => $period,
            'start_date' => $startDate,
            'end_date' => $endDate
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => $summary,
                'contracts' => $varianceReports
            ]
        ]);
    }

    /**
     * Get contracts expiring soon.
     */
    public function expiringSoon(Request $request): JsonResponse
    {
        $days = $request->get('days', 30);
        $contracts = Contract::expiringSoon($days)
            ->with(['category'])
            ->orderBy('end_date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $contracts
        ]);
    }

    /**
     * Get contract statistics.
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total_contracts' => Contract::count(),
            'active_contracts' => Contract::active()->count(),
            'expired_contracts' => Contract::expired()->count(),
            'expiring_soon' => Contract::expiringSoon(30)->count(),
            'by_status' => [
                'active' => Contract::where('status', 'active')->count(),
                'completed' => Contract::where('status', 'completed')->count(),
                'cancelled' => Contract::where('status', 'cancelled')->count(),
                'expired' => Contract::where('status', 'expired')->count(),
            ],
            'total_expected_income' => Contract::active()->get()->sum('expected_income'),
            'total_actual_income' => Contract::active()->get()->sum('actual_income'),
            'total_variance' => Contract::active()->get()->sum('variance'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Renew a contract.
     */
    public function renew(Request $request, Contract $contract): JsonResponse
    {
        $validated = $request->validate([
            'new_end_date' => 'required|date|after:today',
            'new_monthly_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $contract->update([
            'end_date' => $validated['new_end_date'],
            'monthly_amount' => $validated['new_monthly_amount'] ?? $contract->monthly_amount,
            'notes' => $contract->notes . "\n\nRenewed on " . now()->format('Y-m-d') . ": " . ($validated['notes'] ?? 'Contract renewed')
        ]);

        $contract->load(['category']);

        return response()->json([
            'success' => true,
            'message' => 'Contract renewed successfully',
            'data' => $contract
        ]);
    }

    /**
     * Cancel a contract.
     */
    public function cancel(Request $request, Contract $contract): JsonResponse
    {
        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:500',
            'effective_date' => 'nullable|date|after_or_equal:today'
        ]);

        $contract->update([
            'status' => 'cancelled',
            'end_date' => $validated['effective_date'] ?? now(),
            'notes' => $contract->notes . "\n\nCancelled on " . now()->format('Y-m-d') . ": " . $validated['cancellation_reason']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contract cancelled successfully',
            'data' => $contract
        ]);
    }
}
