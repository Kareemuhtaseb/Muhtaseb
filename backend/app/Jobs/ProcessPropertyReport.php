<?php

namespace App\Jobs;

use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProcessPropertyReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 minutes
    public $tries = 3;

    protected $propertyId;
    protected $reportType;
    protected $dateRange;

    /**
     * Create a new job instance.
     */
    public function __construct(int $propertyId, string $reportType, array $dateRange = [])
    {
        $this->propertyId = $propertyId;
        $this->reportType = $reportType;
        $this->dateRange = $dateRange;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting property report generation", [
            'property_id' => $this->propertyId,
            'report_type' => $this->reportType,
            'date_range' => $this->dateRange
        ]);

        try {
            $property = Property::findOrFail($this->propertyId);
            
            $report = $this->generateReport($property);
            
            // Cache the report for 1 hour
            $cacheKey = "property_report_{$this->propertyId}_{$this->reportType}_" . md5(serialize($this->dateRange));
            Cache::put($cacheKey, $report, 3600);

            Log::info("Property report generated successfully", [
                'property_id' => $this->propertyId,
                'report_type' => $this->reportType,
                'cache_key' => $cacheKey
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to generate property report", [
                'property_id' => $this->propertyId,
                'report_type' => $this->reportType,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Generate the report based on type
     */
    private function generateReport(Property $property): array
    {
        switch ($this->reportType) {
            case 'financial':
                return $this->generateFinancialReport($property);
            case 'occupancy':
                return $this->generateOccupancyReport($property);
            case 'maintenance':
                return $this->generateMaintenanceReport($property);
            case 'comprehensive':
                return $this->generateComprehensiveReport($property);
            default:
                throw new \InvalidArgumentException("Unknown report type: {$this->reportType}");
        }
    }

    /**
     * Generate financial report
     */
    private function generateFinancialReport(Property $property): array
    {
        $startDate = $this->dateRange['start'] ?? now()->startOfMonth();
        $endDate = $this->dateRange['end'] ?? now()->endOfMonth();

        return [
            'property' => [
                'id' => $property->id,
                'name' => $property->name,
                'location' => $property->location,
            ],
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'income' => [
                'total' => $property->income()
                    ->whereBetween('date', [$startDate, $endDate])
                    ->sum('amount'),
                'by_category' => $property->income()
                    ->whereBetween('date', [$startDate, $endDate])
                    ->with('category')
                    ->get()
                    ->groupBy('category.name')
                    ->map(function ($items) {
                        return $items->sum('amount');
                    }),
            ],
            'expenses' => [
                'total' => $property->expenses()
                    ->whereBetween('date', [$startDate, $endDate])
                    ->sum('amount'),
                'by_category' => $property->expenses()
                    ->whereBetween('date', [$startDate, $endDate])
                    ->with('category')
                    ->get()
                    ->groupBy('category.name')
                    ->map(function ($items) {
                        return $items->sum('amount');
                    }),
            ],
            'net_income' => 0, // Will be calculated
            'generated_at' => now()->toISOString(),
        ];
    }

    /**
     * Generate occupancy report
     */
    private function generateOccupancyReport(Property $property): array
    {
        return [
            'property' => [
                'id' => $property->id,
                'name' => $property->name,
                'location' => $property->location,
            ],
            'units' => [
                'total' => $property->units()->count(),
                'occupied' => $property->units()->where('status', 'occupied')->count(),
                'available' => $property->units()->where('status', 'available')->count(),
                'maintenance' => $property->units()->where('status', 'maintenance')->count(),
            ],
            'occupancy_rate' => $property->occupancy_rate,
            'recent_leases' => $property->units()
                ->with(['leases' => function ($q) {
                    $q->where('status', 'active')
                      ->orderBy('start_date', 'desc')
                      ->limit(5);
                }, 'leases.tenant'])
                ->get()
                ->pluck('leases')
                ->flatten(),
            'expiring_leases' => $property->units()
                ->whereHas('leases', function ($q) {
                    $q->where('status', 'active')
                      ->where('end_date', '<=', now()->addDays(30));
                })
                ->with(['leases.tenant'])
                ->get(),
            'generated_at' => now()->toISOString(),
        ];
    }

    /**
     * Generate maintenance report
     */
    private function generateMaintenanceReport(Property $property): array
    {
        return [
            'property' => [
                'id' => $property->id,
                'name' => $property->name,
                'location' => $property->location,
            ],
            'maintenance_requests' => [
                'total' => $property->maintenanceRequests()->count(),
                'open' => $property->maintenanceRequests()->where('status', 'open')->count(),
                'in_progress' => $property->maintenanceRequests()->where('status', 'in_progress')->count(),
                'completed' => $property->maintenanceRequests()->where('status', 'completed')->count(),
                'overdue' => $property->maintenanceRequests()->overdue()->count(),
            ],
            'recent_requests' => $property->maintenanceRequests()
                ->with(['assignedTo', 'unit'])
                ->orderBy('request_date', 'desc')
                ->limit(10)
                ->get(),
            'cost_summary' => [
                'total_cost' => $property->maintenanceRequests()->sum('cost'),
                'avg_cost' => $property->maintenanceRequests()->avg('cost'),
            ],
            'generated_at' => now()->toISOString(),
        ];
    }

    /**
     * Generate comprehensive report
     */
    private function generateComprehensiveReport(Property $property): array
    {
        return [
            'property' => [
                'id' => $property->id,
                'name' => $property->name,
                'location' => $property->location,
                'property_type' => $property->propertyType,
            ],
            'financial' => $this->generateFinancialReport($property),
            'occupancy' => $this->generateOccupancyReport($property),
            'maintenance' => $this->generateMaintenanceReport($property),
            'owners' => $property->owners()->wherePivot('status', 'active')->get(),
            'generated_at' => now()->toISOString(),
        ];
    }
}
