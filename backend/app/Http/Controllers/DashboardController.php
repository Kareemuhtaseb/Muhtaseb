<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPropertyReport;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Tenant;
use App\Models\Income;
use App\Models\Expense;
use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard overview with cached data
     */
    public function overview(): JsonResponse
    {
        return Cache::remember('dashboard_overview', 300, function () {
            $stats = [
                'properties' => [
                    'total' => Property::count(),
                    'active' => Property::active()->count(),
                ],
                'units' => [
                    'total' => Unit::count(),
                    'occupied' => Unit::where('status', 'occupied')->count(),
                    'available' => Unit::where('status', 'available')->count(),
                    'maintenance' => Unit::where('status', 'maintenance')->count(),
                ],
                'tenants' => [
                    'total' => Tenant::count(),
                    'active' => Tenant::whereHas('leases', function ($q) {
                        $q->where('status', 'active');
                    })->count(),
                ],
                'financials' => [
                    'monthly_income' => Income::whereMonth('date', now()->month)
                        ->whereYear('date', now()->year)
                        ->sum('amount'),
                    'monthly_expenses' => Expense::whereMonth('date', now()->month)
                        ->whereYear('date', now()->year)
                        ->sum('amount'),
                    'total_income' => Income::sum('amount'),
                    'total_expenses' => Expense::sum('amount'),
                ],
                'maintenance' => [
                    'open_requests' => MaintenanceRequest::whereIn('status', ['open', 'in_progress'])->count(),
                    'overdue_requests' => MaintenanceRequest::overdue()->count(),
                ],
            ];

            // Calculate net income
            $stats['financials']['net_income'] = $stats['financials']['monthly_income'] - $stats['financials']['monthly_expenses'];
            $stats['financials']['total_net_income'] = $stats['financials']['total_income'] - $stats['financials']['total_expenses'];

            return $this->successResponse($stats, 'Dashboard overview retrieved successfully');
        });
    }

    /**
     * Get recent activities
     */
    public function recentActivities(): JsonResponse
    {
        return Cache::remember('dashboard_recent_activities', 180, function () {
            $activities = [
                'recent_income' => Income::with(['property', 'category'])
                    ->orderBy('date', 'desc')
                    ->limit(5)
                    ->get(),
                'recent_expenses' => Expense::with(['property', 'category'])
                    ->orderBy('date', 'desc')
                    ->limit(5)
                    ->get(),
                'recent_maintenance' => MaintenanceRequest::with(['property', 'unit', 'assignedTo'])
                    ->orderBy('request_date', 'desc')
                    ->limit(5)
                    ->get(),
                'expiring_leases' => DB::table('leases')
                    ->join('units', 'leases.unit_id', '=', 'units.id')
                    ->join('properties', 'units.property_id', '=', 'properties.id')
                    ->join('tenants', 'leases.tenant_id', '=', 'tenants.id')
                    ->where('leases.status', 'active')
                    ->where('leases.end_date', '<=', now()->addDays(30))
                    ->select('leases.*', 'units.unit_number', 'properties.name as property_name', 'tenants.name as tenant_name')
                    ->orderBy('leases.end_date', 'asc')
                    ->limit(5)
                    ->get(),
            ];

            return $this->successResponse($activities, 'Recent activities retrieved successfully');
        });
    }

    /**
     * Get property performance metrics
     */
    public function propertyPerformance(): JsonResponse
    {
        return Cache::remember('dashboard_property_performance', 600, function () {
            $performance = Property::with(['units', 'income', 'expenses'])
                ->get()
                ->map(function ($property) {
                    return [
                        'id' => $property->id,
                        'name' => $property->name,
                        'occupancy_rate' => $property->occupancy_rate,
                        'total_income' => $property->total_income,
                        'total_expenses' => $property->total_expenses,
                        'net_income' => $property->net_income,
                        'total_units' => $property->total_units,
                        'occupied_units' => $property->occupied_units,
                    ];
                })
                ->sortByDesc('net_income')
                ->values();

            return $this->successResponse($performance, 'Property performance retrieved successfully');
        });
    }

    /**
     * Get financial summary
     */
    public function financialSummary(Request $request): JsonResponse
    {
        $period = $request->get('period', 'month'); // month, quarter, year
        $cacheKey = "dashboard_financial_summary_{$period}";

        return Cache::remember($cacheKey, 300, function () use ($period) {
            $dateRange = $this->getDateRange($period);

            $summary = [
                'period' => $period,
                'date_range' => $dateRange,
                'income' => [
                    'total' => Income::whereBetween('date', $dateRange)->sum('amount'),
                    'by_category' => Income::whereBetween('date', $dateRange)
                        ->with('category')
                        ->get()
                        ->groupBy('category.name')
                        ->map(function ($items) {
                            return $items->sum('amount');
                        }),
                ],
                'expenses' => [
                    'total' => Expense::whereBetween('date', $dateRange)->sum('amount'),
                    'by_category' => Expense::whereBetween('date', $dateRange)
                        ->with('category')
                        ->get()
                        ->groupBy('category.name')
                        ->map(function ($items) {
                            return $items->sum('amount');
                        }),
                ],
                'monthly_trend' => $this->getMonthlyTrend($dateRange),
            ];

            $summary['net_income'] = $summary['income']['total'] - $summary['expenses']['total'];

            return $this->successResponse($summary, 'Financial summary retrieved successfully');
        });
    }

    /**
     * Generate property report (async)
     */
    public function generatePropertyReport(Request $request): JsonResponse
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'report_type' => 'required|in:financial,occupancy,maintenance,comprehensive',
            'date_range' => 'sometimes|array',
        ]);

        // Dispatch background job
        ProcessPropertyReport::dispatch(
            $request->property_id,
            $request->report_type,
            $request->date_range ?? []
        );

        return $this->successResponse(
            null,
            'Report generation started. Check back in a few minutes.',
            202
        );
    }

    /**
     * Get property report status
     */
    public function getPropertyReport(Request $request): JsonResponse
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'report_type' => 'required|in:financial,occupancy,maintenance,comprehensive',
            'date_range' => 'sometimes|array',
        ]);

        $cacheKey = "property_report_{$request->property_id}_{$request->report_type}_" . md5(serialize($request->date_range ?? []));
        
        $report = Cache::get($cacheKey);
        
        if (!$report) {
            return $this->errorResponse('Report not found or still being generated', 404);
        }

        return $this->successResponse($report, 'Property report retrieved successfully');
    }

    /**
     * Get date range based on period
     */
    private function getDateRange(string $period): array
    {
        switch ($period) {
            case 'month':
                return [now()->startOfMonth(), now()->endOfMonth()];
            case 'quarter':
                return [now()->startOfQuarter(), now()->endOfQuarter()];
            case 'year':
                return [now()->startOfYear(), now()->endOfYear()];
            default:
                return [now()->startOfMonth(), now()->endOfMonth()];
        }
    }

    /**
     * Get monthly trend data
     */
    private function getMonthlyTrend(array $dateRange): array
    {
        $months = [];
        $current = $dateRange[0]->copy();

        while ($current <= $dateRange[1]) {
            $monthKey = $current->format('Y-m');
            $months[$monthKey] = [
                'income' => Income::whereYear('date', $current->year)
                    ->whereMonth('date', $current->month)
                    ->sum('amount'),
                'expenses' => Expense::whereYear('date', $current->year)
                    ->whereMonth('date', $current->month)
                    ->sum('amount'),
            ];
            $current->addMonth();
        }

        return $months;
    }
}
