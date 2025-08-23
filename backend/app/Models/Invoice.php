<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'amount',
        'tax_amount',
        'total_amount',
        'status',
        'description',
        'line_items',
        'notes',
        'paid_date'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'line_items' => 'array'
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function getIsOverdueAttribute()
    {
        return $this->status !== 'paid' && $this->due_date->isPast();
    }

    public function getDaysOverdueAttribute()
    {
        if (!$this->is_overdue) {
            return 0;
        }
        return $this->due_date->diffInDays(now());
    }

    public function getFormattedStatusAttribute()
    {
        if ($this->is_overdue) {
            return 'overdue';
        }
        return $this->status;
    }

    public function getStatusColorAttribute()
    {
        return match($this->formatted_status) {
            'draft' => 'gray',
            'sent' => 'blue',
            'paid' => 'green',
            'overdue' => 'red',
            'cancelled' => 'gray',
            default => 'gray'
        };
    }

    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'paid_date' => now()
        ]);
    }

    public function markAsSent()
    {
        $this->update(['status' => 'sent']);
    }

    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
    }

    public static function generateInvoiceNumber()
    {
        $lastInvoice = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastInvoice ? intval(substr($lastInvoice->invoice_number, 3)) : 0;
        return 'INV' . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
    }
}
