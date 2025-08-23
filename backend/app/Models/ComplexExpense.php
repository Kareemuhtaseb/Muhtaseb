<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplexExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'commercial_complex_id',
        'category',
        'description',
        'amount',
        'expense_date',
        'vendor',
        'invoice_number',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    /**
     * Get the commercial complex that owns the expense.
     */
    public function commercialComplex(): BelongsTo
    {
        return $this->belongsTo(CommercialComplex::class);
    }

    /**
     * Scope for expenses by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for expenses by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('expense_date', [$startDate, $endDate]);
    }

    /**
     * Get formatted amount with currency.
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2) . ' SAR';
    }

    /**
     * Get formatted expense date.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->expense_date->format('Y-m-d');
    }
}
