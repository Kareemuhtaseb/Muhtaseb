<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'lease_id',
        'property_id',
        'unit_id',
        'income_id',
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
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function lease()
    {
        return $this->belongsTo(Lease::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function income()
    {
        return $this->belongsTo(Income::class);
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
            'online' => '🌐',
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

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('method', $method);
    }

    public function scopeWithRelations($query)
    {
        return $query->with(['tenant', 'lease', 'property', 'unit', 'recordedBy']);
    }

    public function scopeWithInvoices($query)
    {
        return $query->with(['invoices' => function ($q) {
            $q->orderBy('application_date', 'desc');
        }]);
    }
}
