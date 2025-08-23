<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'company_id',
        'start_date',
        'end_date',
        'yearly_rent',
        'yearly_rent_with_tax',
        'yearly_services',
        'monthly_rent',
        'monthly_rent_with_tax',
        'monthly_services',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'yearly_rent' => 'decimal:2',
        'yearly_rent_with_tax' => 'decimal:2',
        'yearly_services' => 'decimal:2',
        'monthly_rent' => 'decimal:2',
        'monthly_rent_with_tax' => 'decimal:2',
        'monthly_services' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contract) {
            // Calculate monthly amounts
            $contract->monthly_rent = $contract->yearly_rent / 12;
            $contract->monthly_rent_with_tax = $contract->yearly_rent_with_tax / 12;
            $contract->monthly_services = $contract->yearly_services / 12;
        });

        static::updating(function ($contract) {
            // Recalculate monthly amounts if yearly amounts changed
            if ($contract->isDirty(['yearly_rent', 'yearly_rent_with_tax', 'yearly_services'])) {
                $contract->monthly_rent = $contract->yearly_rent / 12;
                $contract->monthly_rent_with_tax = $contract->yearly_rent_with_tax / 12;
                $contract->monthly_services = $contract->yearly_services / 12;
            }
        });
    }

    /**
     * Get the shop that owns the contract.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the company that owns the contract.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the monthly incomes for this contract.
     */
    public function monthlyIncomes(): HasMany
    {
        return $this->hasMany(MonthlyIncome::class);
    }

    /**
     * Get the income transactions for this contract.
     */
    public function incomeTransactions(): HasMany
    {
        return $this->hasMany(IncomeTransaction::class);
    }

    /**
     * Check if contract is active.
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if contract is expired.
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->end_date->isPast();
    }

    /**
     * Get remaining days in contract.
     */
    public function getRemainingDaysAttribute(): int
    {
        return $this->end_date->diffInDays(Carbon::now());
    }

    /**
     * Get total expected income for a specific month.
     */
    public function getExpectedIncomeForMonth(int $year, int $month): float
    {
        return $this->monthly_rent_with_tax + $this->monthly_services;
    }

    /**
     * Get actual income for a specific month.
     */
    public function getActualIncomeForMonth(int $year, int $month): float
    {
        return $this->monthlyIncomes()
            ->where('year', $year)
            ->where('month', $month)
            ->value('actual_amount') ?? 0;
    }

    /**
     * Get overdue amount for this contract.
     */
    public function getOverdueAmountAttribute(): float
    {
        $currentDate = Carbon::now();
        $totalExpected = 0;
        $totalPaid = 0;

        // Calculate expected vs actual for each month
        for ($month = $this->start_date->month; $month <= $currentDate->month; $month++) {
            $year = $this->start_date->year;
            if ($month > 12) {
                $month = 1;
                $year++;
            }

            $totalExpected += $this->getExpectedIncomeForMonth($year, $month);
            $totalPaid += $this->getActualIncomeForMonth($year, $month);
        }

        return max(0, $totalExpected - $totalPaid);
    }
}
