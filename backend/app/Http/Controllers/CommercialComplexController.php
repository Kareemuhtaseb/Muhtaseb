<?php

namespace App\Http\Controllers;

use App\Models\CommercialComplex;
use App\Models\Shop;
use App\Models\Company;
use App\Models\Contract;
use App\Models\MonthlyIncome;
use App\Models\IncomeTransaction;
use App\Models\ComplexExpense;
use App\Services\ExcelImportService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CommercialComplexController extends Controller
{
    public function index(): JsonResponse
    {
        $complexes = CommercialComplex::withCount(['shops', 'expenses'])
            ->with(['shops' => function ($query) {
                $query->with('activeContract.company');
            }])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $complexes,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $complex = CommercialComplex::with([
            'shops.contracts.company',
            'shops.activeContract.company',
            'expenses',
            'monthlySummaries'
        ])->findOrFail($id);

        // Calculate summary statistics
        $summary = [
            'total_shops' => $complex->shops->count(),
            'occupied_shops' => $complex->shops->where('status', 'occupied')->count(),
            'available_shops' => $complex->shops->where('status', 'available')->count(),
            'active_contracts' => $complex->shops->where('status', 'occupied')->count(),
            'total_monthly_income' => $complex->shops->sum('current_monthly_rent'),
            'total_yearly_income' => $complex->shops->sum('current_monthly_rent') * 12,
        ];

        return response()->json([
            'success' => true,
            'data' => $complex,
            'summary' => $summary,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
        ]);

        $complex = CommercialComplex::create($validated);

        return response()->json([
            'success' => true,
            'data' => $complex,
            'message' => 'Commercial complex created successfully',
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $complex = CommercialComplex::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
        ]);

        $complex->update($validated);

        return response()->json([
            'success' => true,
            'data' => $complex,
            'message' => 'Commercial complex updated successfully',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $complex = CommercialComplex::findOrFail($id);
        $complex->delete();

        return response()->json([
            'success' => true,
            'message' => 'Commercial complex deleted successfully',
        ]);
    }

    public function importExcel(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            $file = $request->file('file');
            $filePath = $file->store('temp');

            $importService = new ExcelImportService();
            $results = $importService->importFromExcel(storage_path('app/' . $filePath));

            // Clean up temp file
            unlink(storage_path('app/' . $filePath));

            return response()->json([
                'success' => true,
                'message' => 'Excel file imported successfully',
                'results' => $results,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to import Excel file: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function dashboard(int $id): JsonResponse
    {
        $complex = CommercialComplex::findOrFail($id);

        // Get current month/year
        $currentDate = Carbon::now();
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;

        // Calculate monthly statistics
        $monthlyStats = [
            'expected_income' => 0,
            'actual_income' => 0,
            'expenses' => 0,
            'net_income' => 0,
            'overdue_amount' => 0,
        ];

        // Get active contracts for this month
        $activeContracts = Contract::whereHas('shop', function ($query) use ($id) {
            $query->where('commercial_complex_id', $id);
        })
        ->where('status', 'active')
        ->where('start_date', '<=', $currentDate)
        ->where('end_date', '>=', $currentDate)
        ->with(['shop', 'company', 'monthlyIncomes'])
        ->get();

        foreach ($activeContracts as $contract) {
            $monthlyExpected = $contract->monthly_rent_with_tax + $contract->monthly_services;
            $monthlyStats['expected_income'] += $monthlyExpected;

            // Get actual income for current month
            $actualIncome = $contract->monthlyIncomes()
                ->where('year', $currentYear)
                ->where('month', $currentMonth)
                ->sum('actual_amount');
            $monthlyStats['actual_income'] += $actualIncome;

            // Calculate overdue amount
            $monthlyStats['overdue_amount'] += $contract->overdue_amount;
        }

        // Get expenses for current month
        $monthlyStats['expenses'] = $complex->expenses()
            ->whereYear('expense_date', $currentYear)
            ->whereMonth('expense_date', $currentMonth)
            ->sum('amount');

        $monthlyStats['net_income'] = $monthlyStats['actual_income'] - $monthlyStats['expenses'];

        // Get recent transactions
        $recentTransactions = IncomeTransaction::whereHas('contract.shop', function ($query) use ($id) {
            $query->where('commercial_complex_id', $id);
        })
        ->with(['contract.shop', 'contract.company'])
        ->orderBy('transaction_date', 'desc')
        ->limit(10)
        ->get();

        // Get overdue contracts
        $overdueContracts = $activeContracts->filter(function ($contract) {
            return $contract->overdue_amount > 0;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'complex' => $complex,
                'monthly_stats' => $monthlyStats,
                'active_contracts_count' => $activeContracts->count(),
                'overdue_contracts_count' => $overdueContracts->count(),
                'recent_transactions' => $recentTransactions,
                'overdue_contracts' => $overdueContracts->take(5),
            ],
        ]);
    }
}
