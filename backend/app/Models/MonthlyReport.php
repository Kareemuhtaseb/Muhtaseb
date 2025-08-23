<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'month',
        'year',
        'total_amount',
        'status',
        'report_data',
        'sent_at'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'report_data' => 'array',
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    // Scopes
    public function scopeGenerated($query)
    {
        return $query->where('status', 'generated');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForMonth($query, $month, $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }

    public function scopeForOwner($query, $ownerId)
    {
        return $query->where('owner_id', $ownerId);
    }

    // Accessor methods
    public function getMonthNameAttribute()
    {
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        
        return $months[$this->month] ?? 'Unknown';
    }

    public function getPeriodAttribute()
    {
        return "{$this->month_name} {$this->year}";
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->total_amount, 2);
    }

    public function getStatusColorAttribute()
    {
        return [
            'generated' => 'green',
            'sent' => 'blue',
            'pending' => 'yellow'
        ][$this->status] ?? 'gray';
    }

    // Methods
    public function markAsSent()
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);
    }

    public function regenerate()
    {
        // This would recalculate the total_amount based on current data
        $this->update([
            'status' => 'generated',
            'sent_at' => null
        ]);
    }

    public function isSent()
    {
        return $this->status === 'sent';
    }

    public function isGenerated()
    {
        return $this->status === 'generated';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }
}
