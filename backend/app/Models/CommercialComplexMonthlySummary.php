<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommercialComplexMonthlySummary extends Model
{
    use HasFactory;

    protected $table = 'commercial_complex_monthly_summaries';

    protected $fillable = [
        'commercial_complex_id',
        'year',
        'month',
        'total_expected_income',
        'total_actual_income',
        'total_expenses',
        'net_income',
        'active_contracts_count',
        'paid_contracts_count',
        'overdue_contracts_count',
    ];

    protected $casts = [
        'total_expected_income' => 'decimal:2',
        'total_actual_income' => 'decimal:2',
        'total_expenses' => 'decimal:2',
        'net_income' => 'decimal:2',
    ];

    /**
     * Get the commercial complex that owns the monthly summary.
     */
    public function commercialComplex(): BelongsTo
    {
        return $this->belongsTo(CommercialComplex::class);
    }

    /**
     * Get formatted month name.
     */
    public function getMonthNameAttribute(): string
    {
        return date('F', mktime(0, 0, 0, $this->month, 1));
    }

    /**
     * Get formatted period.
     */
    public function getPeriodAttribute(): string
    {
        return $this->month_name . ' ' . $this->year;
    }

    /**
     * Get collection rate percentage.
     */
    public function getCollectionRateAttribute(): float
    {
        if ($this->total_expected_income == 0) {
            return 0;
        }
        return ($this->total_actual_income / $this->total_expected_income) * 100;
    }
}
