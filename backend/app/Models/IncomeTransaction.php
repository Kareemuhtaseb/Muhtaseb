<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncomeTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'amount',
        'transaction_date',
        'description',
        'type',
        'payment_method',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    /**
     * Get the contract that owns the income transaction.
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
     * Scope for rent transactions.
     */
    public function scopeRent($query)
    {
        return $query->where('type', 'rent');
    }

    /**
     * Scope for services transactions.
     */
    public function scopeServices($query)
    {
        return $query->where('type', 'services');
    }

    /**
     * Scope for penalty transactions.
     */
    public function scopePenalty($query)
    {
        return $query->where('type', 'penalty');
    }

    /**
     * Get formatted amount with currency.
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2) . ' SAR';
    }

    /**
     * Get formatted transaction date.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->transaction_date->format('Y-m-d');
    }
}
