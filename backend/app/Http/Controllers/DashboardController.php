<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Owner;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Contract;
use App\Models\MaintenanceRequest;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        return Cache::remember('dashboard_overview', 300, function () {
            $stats = [
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
                'properties' => [
                    'total' => Property::count(),
                    'active' => Property::active()->count(),
                    'residential' => Property::where('property_type', 'residential')->count(),
                    'commercial' => Property::where('property_type', 'commercial')->count(),
                ],
                'units' => [
                    'total' => Unit::count(),
                    'occupied' => Unit::where('status', 'occupied')->count(),
                    'available' => Unit::where('status', 'available')->count(),
                    'maintenance' => Unit::where('status', 'maintenance')->count(),
                ],
                'contracts' => [
                    'total' => Contract::count(),
                    'active' => Contract::active()->count(),
                    'expired' => Contract::expired()->count(),
                    'expiring_soon' => Contract::expiringSoon(30)->count(),
                ],
                'owners' => [
                    'total' => Owner::count(),
                    'active' => Owner::where('status', 'active')->count(),
                ],
                'maintenance' => [
                    'open_requests' => MaintenanceRequest::whereIn('status', ['open', 'in_progress'])->count(),
                    'overdue_requests' => MaintenanceRequest::where('status', 'overdue')->count(),
                ],
            ];

            $overview = [
                'total_income' => $stats['financials']['total_income'],
                'total_expenses' => $stats['financials']['total_expenses'],
                'net_income' => $stats['financials']['total_income'] - $stats['financials']['total_expenses'],
                'monthly_income' => $stats['financials']['monthly_income'],
                'monthly_expenses' => $stats['financials']['monthly_expenses'],
                'properties' => $stats['properties']['total'],
                'units' => $stats['units']['total'],
                'occupied_units' => $stats['units']['occupied'],
                'occupancy_rate' => $stats['units']['total'] > 0 ? ($stats['units']['occupied'] / $stats['units']['total']) * 100 : 0,
                'active_contracts' => $stats['contracts']['active'],
                'expiring_contracts' => $stats['contracts']['expiring_soon']
            ];

            $recentActivities = [
                'recent_income' => Income::with(['category', 'property', 'unit'])
                    ->orderBy('date', 'desc')
                    ->limit(5)
                    ->get(),
                'recent_expenses' => Expense::with(['category', 'property', 'unit'])
                    ->orderBy('date', 'desc')
                    ->limit(5)
                    ->get(),
                'recent_maintenance' => MaintenanceRequest::with(['assignedTo'])
                    ->orderBy('request_date', 'desc')
                    ->limit(5)
                    ->get(),
                'expiring_contracts' => Contract::expiringSoon(30)
                    ->with(['property', 'unit', 'category'])
                    ->orderBy('end_date', 'asc')
                    ->limit(5)
                    ->get(),
            ];

            $owner_distributions = Owner::with(['distributions'])
                ->get()
                ->map(function ($owner) {
                    return [
                        'id' => $owner->id,
                        'name' => $owner->name,
                        'share_percentage' => $owner->share_percentage,
                        'total_distributions' => $owner->distributions->sum('amount'),
                        'status' => $owner->status,
                    ];
                })
                ->sortByDesc('total_distributions')
                ->take(5)
                ->values();

            $property_performance = Property::with(['units', 'contracts', 'income', 'expenses'])
                ->get()
                ->map(function ($property) {
                    return [
                        'id' => $property->id,
                        'name' => $property->name,
                        'property_type' => $property->property_type,
                        'occupancy_rate' => $property->occupancy_rate,
                        'total_income' => $property->total_income,
                        'total_expenses' => $property->total_expenses,
                        'net_income' => $property->net_income,
                        'roi' => $property->roi,
                        'variance' => $property->variance,
                        'variance_percentage' => $property->variance_percentage,
                    ];
                })
                ->sortByDesc('net_income')
                ->take(5)
                ->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'overview' => $overview,
                    'stats' => $stats,
                    'recent_activities' => $recentActivities,
                    'owner_distributions' => $owner_distributions,
                    'property_performance' => $property_performance,
                ]
            ]);
        });
    }

    /**
     * Get owner performance metrics
     */
    public function ownerPerformance(): JsonResponse
    {
        return Cache::remember('dashboard_owner_performance', 600, function () {
            $performance = Owner::with(['distributions'])
                ->get()
                ->map(function ($owner) {
                    return [
                        'id' => $owner->id,
                        'name' => $owner->name,
                        'share_percentage' => $owner->share_percentage,
                        'total_distributions' => $owner->distributions->sum('amount'),
                        'status' => $owner->status,
                    ];
                })
                ->sortByDesc('total_distributions')
                ->values();

            return $this->successResponse($performance, 'Owner performance retrieved successfully');
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
                        'property_type' => $property->property_type,
                        'occupancy_rate' => $property->occupancy_rate,
                        'total_income' => $property->total_income,
                        'total_expenses' => $property->total_expenses,
                        'net_income' => $property->net_income,
                        'roi' => $property->roi,
                        'total_units' => $property->units()->count(),
                        'occupied_units' => $property->units()->where('status', 'occupied')->count(),
                    ];
                })
                ->sortByDesc('net_income')
                ->values();

            return $this->successResponse($performance, 'Property performance retrieved successfully');
        });
    }

    /**
     * Success response helper
     */
    private function successResponse($data, $message = 'Success', $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Error response helper
     */
    private function errorResponse($message = 'Error', $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }
}
