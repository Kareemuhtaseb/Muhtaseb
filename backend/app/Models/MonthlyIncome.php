<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyIncome extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'year',
        'month',
        'expected_amount',
        'actual_amount',
        'payment_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'expected_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    /**
     * Get the contract that owns the monthly income.
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Get the shop through contract.
     */
    public function shop()
    {
        return $this->contract->shop;
    }

    /**
     * Get the company through contract.
     */
    public function company()
    {
        return $this->contract->company;
    }

    /**
     * Get the difference between expected and actual.
     */
    public function getDifferenceAttribute(): float
    {
        return ($this->actual_amount ?? 0) - $this->expected_amount;
    }

    /**
     * Check if payment is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        if ($this->status === 'paid') {
            return false;
        }

        $dueDate = \Carbon\Carbon::create($this->year, $this->month, 1)->endOfMonth();
        return $dueDate->isPast();
    }

    /**
     * Get overdue days.
     */
    public function getOverdueDaysAttribute(): int
    {
        if (!$this->is_overdue) {
            return 0;
        }

        $dueDate = \Carbon\Carbon::create($this->year, $this->month, 1)->endOfMonth();
        return $dueDate->diffInDays(\Carbon\Carbon::now());
    }
}
