<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'contact',
        'lease_start',
        'lease_end',
        'deposit'
    ];

    protected $casts = [
        'lease_start' => 'date',
        'lease_end' => 'date',
        'deposit' => 'decimal:2'
    ];

    public function leases()
    {
        return $this->hasMany(Lease::class);
    }

    public function income()
    {
        return $this->hasMany(Income::class);
    }

    public function getCurrentLeaseAttribute()
    {
        return $this->leases()
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where(function($query) {
                $query->where('end_date', '>=', now())
                      ->orWhereNull('end_date');
            })
            ->first();
    }

    public function getCurrentUnitAttribute()
    {
        $currentLease = $this->current_lease;
        return $currentLease ? $currentLease->unit : null;
    }

    public function getTotalPaidAttribute()
    {
        return $this->income()->sum('amount');
    }

    public function getContactArrayAttribute()
    {
        return json_decode($this->contact, true) ?? [];
    }
}
