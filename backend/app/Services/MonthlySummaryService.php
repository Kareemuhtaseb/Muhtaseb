<?php

namespace App\Services;

use App\Models\Owner;
use App\Models\Property;
use App\Models\MonthlySummary;
use App\Models\Income;
use App\Models\Expense;
use App\Models\OwnerDistribution;
use App\Models\Unit;
use App\Models\Tenant;
use App\Models\MaintenanceRequest;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MonthlySummaryService
{
    public function generateOwnerSummary(Owner $owner, $month, $year)
    {
        $monthYear = sprintf('%04d-%02d', $year, $month);
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        // Get owner's properties with ownership percentages
        $properties = $owner->properties()
            ->wherePivot('status', 'active')
            ->get();
        
        $summary = [
            'owner' => $owner,
            'month_year' => $monthYear,
            'month_name' => $startDate->format('F Y'),
            'properties' => [],
            'total_income' => 0,
            'total_expenses' => 0,
            'net_income' => 0,
            'owner_share' => 0,
            'distributions' => 0,
            'balance_owed' => 0,
            'income_by_category' => [],
            'expenses_by_category' => [],
            'maintenance_summary' => [],
            'occupancy_summary' => [],
            'tenant_summary' => []
        ];
        
        foreach ($properties as $property) {
            $ownershipPercentage = $property->pivot->ownership_percentage;
            
            // Get detailed property financial data
            $propertyIncome = Income::where('property_id', $property->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->with(['category', 'tenant', 'unit'])
                ->get();
                
            $propertyExpenses = Expense::where('property_id', $property->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->with(['category'])
                ->get();
                
            $totalIncome = $propertyIncome->sum('amount');
            $totalExpenses = $propertyExpenses->sum('amount');
            $propertyNetIncome = $totalIncome - $totalExpenses;
            $ownerShare = ($propertyNetIncome * $ownershipPercentage) / 100;
            
            // Get property occupancy data
            $units = $property->units()->with(['leases.tenant'])->get();
            $totalUnits = $units->count();
            $occupiedUnits = $units->where('status', 'occupied')->count();
            $vacantUnits = $units->where('status', 'available')->count();
            $maintenanceUnits = $units->where('status', 'maintenance')->count();
            $occupancyRate = $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100, 2) : 0;
            
            // Get maintenance requests
            $maintenanceRequests = MaintenanceRequest::where('property_id', $property->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->with(['unit', 'tenant'])
                ->get();
                
            // Get tenant information
            $tenants = Tenant::whereHas('leases', function($query) use ($property) {
                $query->whereHas('unit', function($q) use ($property) {
                    $q->where('property_id', $property->id);
                });
            })->with(['leases' => function($query) use ($property) {
                $query->whereHas('unit', function($q) use ($property) {
                    $q->where('property_id', $property->id);
                });
            }])->get();
            
            $propertyData = [
                'property' => $property,
                'ownership_percentage' => $ownershipPercentage,
                'income' => $totalIncome,
                'expenses' => $totalExpenses,
                'net_income' => $propertyNetIncome,
                'owner_share' => $ownerShare,
                'total_units' => $totalUnits,
                'occupied_units' => $occupiedUnits,
                'vacant_units' => $vacantUnits,
                'maintenance_units' => $maintenanceUnits,
                'occupancy_rate' => $occupancyRate,
                'income_details' => $propertyIncome,
                'expense_details' => $propertyExpenses,
                'maintenance_requests' => $maintenanceRequests,
                'tenants' => $tenants,
                'income_by_category' => $this->groupByCategory($propertyIncome),
                'expenses_by_category' => $this->groupByCategory($propertyExpenses)
            ];
            
            $summary['properties'][] = $propertyData;
            $summary['total_income'] += $totalIncome;
            $summary['total_expenses'] += $totalExpenses;
            $summary['net_income'] += $propertyNetIncome;
            $summary['owner_share'] += $ownerShare;
        }
        
        // Get distributions for this month
        $distributions = OwnerDistribution::where('owner_id', $owner->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();
            
        $summary['distributions'] = $distributions->sum('amount');
        $summary['balance_owed'] = $summary['owner_share'] - $summary['distributions'];
        $summary['distribution_details'] = $distributions;
        
        // Aggregate income and expenses by category across all properties
        $summary['income_by_category'] = $this->aggregateByCategory($summary['properties'], 'income_by_category');
        $summary['expenses_by_category'] = $this->aggregateByCategory($summary['properties'], 'expenses_by_category');
        
        // Maintenance summary across all properties
        $summary['maintenance_summary'] = $this->aggregateMaintenanceData($summary['properties']);
        
        // Occupancy summary
        $summary['occupancy_summary'] = [
            'total_units' => collect($summary['properties'])->sum('total_units'),
            'occupied_units' => collect($summary['properties'])->sum('occupied_units'),
            'vacant_units' => collect($summary['properties'])->sum('vacant_units'),
            'maintenance_units' => collect($summary['properties'])->sum('maintenance_units'),
            'overall_occupancy_rate' => collect($summary['properties'])->sum('total_units') > 0 
                ? round((collect($summary['properties'])->sum('occupied_units') / collect($summary['properties'])->sum('total_units')) * 100, 2)
                : 0
        ];
        
        return $summary;
    }
    
    private function groupByCategory($transactions)
    {
        return $transactions->groupBy('category.name')
            ->map(function($items) {
                return [
                    'count' => $items->count(),
                    'total' => $items->sum('amount'),
                    'transactions' => $items
                ];
            });
    }
    
    private function aggregateByCategory($properties, $categoryKey)
    {
        $aggregated = [];
        
        foreach ($properties as $property) {
            foreach ($property[$categoryKey] as $categoryName => $data) {
                if (!isset($aggregated[$categoryName])) {
                    $aggregated[$categoryName] = [
                        'count' => 0,
                        'total' => 0,
                        'transactions' => collect()
                    ];
                }
                
                $aggregated[$categoryName]['count'] += $data['count'];
                $aggregated[$categoryName]['total'] += $data['total'];
                $aggregated[$categoryName]['transactions'] = $aggregated[$categoryName]['transactions']->merge($data['transactions']);
            }
        }
        
        return $aggregated;
    }
    
    private function aggregateMaintenanceData($properties)
    {
        $allRequests = collect();
        $statusCounts = ['open' => 0, 'in_progress' => 0, 'completed' => 0, 'cancelled' => 0];
        $priorityCounts = ['low' => 0, 'medium' => 0, 'high' => 0, 'urgent' => 0];
        
        foreach ($properties as $property) {
            $allRequests = $allRequests->merge($property['maintenance_requests']);
        }
        
        foreach ($allRequests as $request) {
            $statusCounts[$request->status] = ($statusCounts[$request->status] ?? 0) + 1;
            $priorityCounts[$request->priority] = ($priorityCounts[$request->priority] ?? 0) + 1;
        }
        
        return [
            'total_requests' => $allRequests->count(),
            'status_breakdown' => $statusCounts,
            'priority_breakdown' => $priorityCounts,
            'requests' => $allRequests
        ];
    }
    
    public function generateAllOwnersSummary($month, $year)
    {
        $owners = Owner::with(['properties' => function($query) {
            $query->wherePivot('status', 'active');
        }])->get();
        
        $summaries = [];
        $totalSummary = [
            'total_income' => 0,
            'total_expenses' => 0,
            'net_income' => 0,
            'total_units' => 0,
            'occupied_units' => 0,
            'total_maintenance_requests' => 0
        ];
        
        foreach ($owners as $owner) {
            if ($owner->properties->count() > 0) {
                $summary = $this->generateOwnerSummary($owner, $month, $year);
                $summaries[] = $summary;
                
                $totalSummary['total_income'] += $summary['total_income'];
                $totalSummary['total_expenses'] += $summary['total_expenses'];
                $totalSummary['net_income'] += $summary['net_income'];
                $totalSummary['total_units'] += $summary['occupancy_summary']['total_units'];
                $totalSummary['occupied_units'] += $summary['occupancy_summary']['occupied_units'];
                $totalSummary['total_maintenance_requests'] += $summary['maintenance_summary']['total_requests'];
            }
        }
        
        return [
            'owners' => $summaries,
            'total_summary' => $totalSummary,
            'month' => Carbon::create($year, $month, 1)->format('F Y')
        ];
    }
}
