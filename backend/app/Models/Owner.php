<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'share_percentage',
        'email',
        'phone',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'share_percentage' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function distributions()
    {
        return $this->hasMany(OwnerDistribution::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function monthlyReports()
    {
        return $this->hasMany(MonthlyReport::class);
    }

    // Many-to-many relationships
    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_owner')
                    ->withPivot('ownership_percentage', 'ownership_start_date', 'ownership_end_date', 'status', 'notes')
                    ->withTimestamps();
    }

    // Belongs to relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Accessor methods
    public function getExpectedMonthlyIncomeAttribute()
    {
        // Calculate expected income based on share percentage
        $totalMonthlyIncome = Income::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');
        
        return ($totalMonthlyIncome * $this->share_percentage) / 100;
    }

    public function getActualDistributedAmountAttribute()
    {
        return $this->distributions()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');
    }

    public function getBalanceOwedAttribute()
    {
        return $this->expected_monthly_income - $this->actual_distributed_amount;
    }

    public function getTotalDistributedAttribute()
    {
        return $this->distributions()->sum('amount');
    }

    public function getActivePropertiesAttribute()
    {
        return $this->properties()->wherePivot('status', 'active')->count();
    }

    public function getTotalOwnershipPercentageAttribute()
    {
        return $this->properties()->wherePivot('status', 'active')->sum('pivot.ownership_percentage');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereHas('properties', function ($q) {
            $q->where('status', 'active');
        });
    }

    public function scopeWithProperties($query)
    {
        return $query->with(['properties' => function ($q) {
            $q->where('status', 'active');
        }]);
    }

    public function scopeWithDistributions($query)
    {
        return $query->with(['distributions' => function ($q) {
            $q->orderBy('date', 'desc');
        }]);
    }

    public function scopeWithInvoices($query)
    {
        return $query->with(['invoices' => function ($q) {
            $q->orderBy('invoice_date', 'desc');
        }]);
    }
}
