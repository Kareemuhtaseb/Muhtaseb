<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'business_number',
        'contact_person',
        'contact_phone',
        'contact_email',
        'address',
        'business_type',
    ];

    /**
     * Get the contracts for this company.
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Get the active contracts for this company.
     */
    public function activeContracts(): HasMany
    {
        return $this->hasMany(Contract::class)->where('status', 'active');
    }

    /**
     * Get total monthly rent for this company.
     */
    public function getTotalMonthlyRentAttribute(): float
    {
        return $this->activeContracts()->sum('monthly_rent_with_tax');
    }

    /**
     * Get total yearly rent for this company.
     */
    public function getTotalYearlyRentAttribute(): float
    {
        return $this->activeContracts()->sum('yearly_rent_with_tax');
    }

    /**
     * Get shops rented by this company.
     */
    public function getRentedShopsAttribute()
    {
        return $this->activeContracts()->with('shop')->get()->pluck('shop');
    }
}
