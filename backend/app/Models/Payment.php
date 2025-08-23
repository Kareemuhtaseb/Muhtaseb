<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'income_id',
        'contract_id',
        'payment_date',
        'amount',
        'method',
        'reference_number',
        'notes',
        'status',
        'recorded_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function income()
    {
        return $this->belongsTo(Income::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Many-to-many relationships
    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'invoice_payments')
                    ->withPivot('amount_applied', 'application_date', 'notes')
                    ->withTimestamps();
    }

    // Accessor methods
    public function getFormattedAmountAttribute()
    {
        return '$' . number_format($this->amount, 2);
    }

    public function getFormattedDateAttribute()
    {
        return $this->payment_date->format('M d, Y');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'completed' => 'success',
            'pending' => 'warning',
            'failed' => 'danger',
            'refunded' => 'info',
            default => 'secondary'
        };
    }

    public function getMethodIconAttribute()
    {
        return match($this->method) {
            'cash' => '💵',
            'check' => '📄',
            'bank_transfer' => '🏦',
            'credit_card' => '💳',
            'debit_card' => '💳',
            'paypal' => '🔵',
            'stripe' => '💳',
            'other' => '📋',
            default => '💰'
        };
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('method', $method);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }

    public function scopeByAmountRange($query, $minAmount, $maxAmount)
    {
        return $query->whereBetween('amount', [$minAmount, $maxAmount]);
    }

    public function scopeWithDetails($query)
    {
        return $query->with(['income', 'contract', 'recordedBy']);
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (!$payment->recorded_by) {
                $payment->recorded_by = auth()->id();
            }
        });
    }
}
