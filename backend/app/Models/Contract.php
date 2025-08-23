<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_number',
        'title',
        'description',
        'property_id',
        'unit_id',
        'client_name',
        'client_email',
        'client_phone',
        'start_date',
        'end_date',
        'monthly_amount',
        'total_amount',
        'payment_frequency', // monthly, quarterly, annually
        'status', // active, completed, cancelled, expired
        'category_id',
        'notes',
        'auto_renew',
        'renewal_terms'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'monthly_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'auto_renew' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function income()
    {
        return $this->hasMany(Income::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Accessors
    public function getExpectedIncomeAttribute()
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        $months = $this->start_date->diffInMonths($this->end_date) + 1;
        return $this->monthly_amount * $months;
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

    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && 
               $this->start_date <= now() && 
               $this->end_date >= now();
    }

    public function getIsExpiredAttribute()
    {
        return $this->end_date < now();
    }

    public function getDaysRemainingAttribute()
    {
        if ($this->is_expired) {
            return 0;
        }
        return now()->diffInDays($this->end_date);
    }

    public function getMonthsRemainingAttribute()
    {
        if ($this->is_expired) {
            return 0;
        }
        return now()->diffInMonths($this->end_date);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', now());
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('status', 'active')
                    ->where('end_date', '<=', now()->addDays($days))
                    ->where('end_date', '>=', now());
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByProperty($query, $propertyId)
    {
        return $query->where('property_id', $propertyId);
    }

    public function scopeByUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_date', [$startDate, $endDate]);
    }

    public function scopeWithIncome($query)
    {
        return $query->with(['income', 'category', 'property', 'unit']);
    }

    // Methods
    public function calculateExpectedIncomeForPeriod($startDate, $endDate)
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        $contractStart = max($this->start_date, $startDate);
        $contractEnd = min($this->end_date, $endDate);

        if ($contractStart > $contractEnd) {
            return 0;
        }

        $months = $contractStart->diffInMonths($contractEnd) + 1;
        return $this->monthly_amount * $months;
    }

    public function getIncomeVarianceReport()
    {
        $expected = $this->expected_income;
        $actual = $this->actual_income;
        $variance = $this->variance;
        $percentage = $this->variance_percentage;

        return [
            'contract_id' => $this->id,
            'contract_number' => $this->contract_number,
            'title' => $this->title,
            'client_name' => $this->client_name,
            'property_name' => $this->property->name ?? 'N/A',
            'unit_number' => $this->unit->unit_number ?? 'N/A',
            'expected_income' => $expected,
            'actual_income' => $actual,
            'variance' => $variance,
            'variance_percentage' => $percentage,
            'status' => $variance > 0 ? 'underperforming' : ($variance < 0 ? 'overperforming' : 'on_target'),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_active' => $this->is_active
        ];
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contract) {
            if (!$contract->contract_number) {
                $contract->contract_number = 'CTR-' . date('Y') . '-' . str_pad(static::whereYear('created_at', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
