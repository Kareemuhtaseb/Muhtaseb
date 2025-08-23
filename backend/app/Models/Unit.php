<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'unit_number',
        'unit_type', // apartment, office, shop, warehouse, etc.
        'status', // available, occupied, maintenance, reserved
        'area',
        'bedrooms',
        'bathrooms',
        'monthly_rent',
        'description',
        'amenities',
        'notes'
    ];

    protected $casts = [
        'area' => 'decimal:2',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'monthly_rent' => 'decimal:2',
        'amenities' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function income()
    {
        return $this->hasMany(Income::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    // Accessors
    public function getIsOccupiedAttribute()
    {
        return $this->status === 'occupied';
    }

    public function getIsAvailableAttribute()
    {
        return $this->status === 'available';
    }

    public function getIsUnderMaintenanceAttribute()
    {
        return $this->status === 'maintenance';
    }

    public function getActiveContractAttribute()
    {
        return $this->contracts()->active()->first();
    }

    public function getExpectedIncomeAttribute()
    {
        return $this->contracts()->active()->get()->sum('expected_income');
    }

    public function getActualIncomeAttribute()
    {
        return $this->income()->sum('amount');
    }

    public function getVarianceAttribute()
    {
        return $this->expected_income - $this->actual_income;
    }

    public function getVariancePercentageAttribute()
    {
        if ($this->expected_income == 0) {
            return 0;
        }
        return ($this->variance / $this->expected_income) * 100;
    }

    public function getOccupancyRateAttribute()
    {
        return $this->is_occupied ? 100 : 0;
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied');
    }

    public function scopeUnderMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('unit_type', $type);
    }

    public function scopeByProperty($query, $propertyId)
    {
        return $query->where('property_id', $propertyId);
    }

    public function scopeWithDetails($query)
    {
        return $query->with(['property', 'contracts', 'income', 'expenses']);
    }

    // Methods
    public function getFinancialReport($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? \Carbon\Carbon::parse($startDate) : now()->startOfYear();
        $endDate = $endDate ? \Carbon\Carbon::parse($endDate) : now()->endOfYear();

        $income = $this->income()->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $expenses = $this->expenses()->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $expectedIncome = $this->contracts()->active()->get()->sum(function($contract) use ($startDate, $endDate) {
            return $contract->calculateExpectedIncomeForPeriod($startDate, $endDate);
        });

        return [
            'unit_id' => $this->id,
            'unit_number' => $this->unit_number,
            'property_name' => $this->property->name ?? 'N/A',
            'period' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d')
            ],
            'income' => [
                'expected' => $expectedIncome,
                'actual' => $income,
                'variance' => $expectedIncome - $income,
                'variance_percentage' => $expectedIncome > 0 ? (($expectedIncome - $income) / $expectedIncome) * 100 : 0
            ],
            'expenses' => $expenses,
            'net_income' => $income - $expenses,
            'status' => $this->status,
            'monthly_rent' => $this->monthly_rent
        ];
    }
}
