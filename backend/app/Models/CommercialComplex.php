<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommercialComplex extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'contact_person',
        'contact_phone',
        'contact_email',
    ];

    /**
     * Get the shops for this commercial complex.
     */
    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class);
    }

    /**
     * Get the expenses for this commercial complex.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(ComplexExpense::class);
    }

    /**
     * Get the monthly summaries for this commercial complex.
     */
    public function monthlySummaries(): HasMany
    {
        return $this->hasMany(CommercialComplexMonthlySummary::class);
    }

    /**
     * Get active contracts count.
     */
    public function getActiveContractsCountAttribute(): int
    {
        return $this->shops()
            ->whereHas('contracts', function ($query) {
                $query->where('status', 'active');
            })
            ->count();
    }

    /**
     * Get total monthly income.
     */
    public function getTotalMonthlyIncomeAttribute(): float
    {
        return $this->shops()
            ->whereHas('contracts', function ($query) {
                $query->where('status', 'active');
            })
            ->with('contracts')
            ->get()
            ->sum(function ($shop) {
                return $shop->contracts->sum('monthly_rent_with_tax');
            });
    }
}
