<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'property_type', // residential, commercial, industrial, mixed
        'status', // active, inactive, sold, under_construction
        'purchase_date',
        'purchase_price',
        'current_value',
        'total_area',
        'units_count',
        'description',
        'notes'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
        'current_value' => 'decimal:2',
        'total_area' => 'decimal:2',
        'units_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
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

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function owners()
    {
        return $this->belongsToMany(Owner::class, 'property_owners')
                    ->withPivot('ownership_percentage', 'purchase_date', 'purchase_price')
                    ->withTimestamps();
    }

    // Accessors
    public function getTotalIncomeAttribute()
    {
        return $this->income()->sum('amount');
    }

    public function getTotalExpensesAttribute()
    {
        return $this->expenses()->sum('amount');
    }

    public function getNetIncomeAttribute()
    {
        return $this->total_income - $this->total_expenses;
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
        if ($this->units_count == 0) {
            return 0;
        }
        $occupiedUnits = $this->units()->where('status', 'occupied')->count();
        return ($occupiedUnits / $this->units_count) * 100;
    }

    public function getRoiAttribute()
    {
        if ($this->purchase_price == 0) {
            return 0;
        }
        return ($this->net_income / $this->purchase_price) * 100;
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('property_type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeWithFinancials($query)
    {
        return $query->with(['contracts', 'income', 'expenses', 'units']);
    }

    // Methods
    public function getFinancialReport($startDate = null, $endDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : now()->startOfYear();
        $endDate = $endDate ? Carbon::parse($endDate) : now()->endOfYear();

        $income = $this->income()->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $expenses = $this->expenses()->whereBetween('date', [$startDate, $endDate])->sum('amount');
        $expectedIncome = $this->contracts()->active()->get()->sum(function($contract) use ($startDate, $endDate) {
            return $contract->calculateExpectedIncomeForPeriod($startDate, $endDate);
        });

        return [
            'property_id' => $this->id,
            'property_name' => $this->name,
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
            'roi' => $this->purchase_price > 0 ? (($income - $expenses) / $this->purchase_price) * 100 : 0,
            'occupancy_rate' => $this->occupancy_rate
        ];
    }

    public function getIncomeVarianceReport()
    {
        $expected = $this->expected_income;
        $actual = $this->actual_income;
        $variance = $this->variance;
        $percentage = $this->variance_percentage;

        return [
            'property_id' => $this->id,
            'property_name' => $this->name,
            'property_type' => $this->property_type,
            'expected_income' => $expected,
            'actual_income' => $actual,
            'variance' => $variance,
            'variance_percentage' => $percentage,
            'status' => $variance > 0 ? 'underperforming' : ($variance < 0 ? 'overperforming' : 'on_target'),
            'occupancy_rate' => $this->occupancy_rate,
            'roi' => $this->roi,
            'is_active' => $this->is_active
        ];
    }
}
