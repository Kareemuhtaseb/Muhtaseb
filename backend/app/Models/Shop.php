<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'commercial_complex_id',
        'shop_number',
        'shop_name',
        'area',
        'location_description',
        'status',
    ];

    protected $casts = [
        'area' => 'decimal:2',
    ];

    /**
     * Get the commercial complex that owns the shop.
     */
    public function commercialComplex(): BelongsTo
    {
        return $this->belongsTo(CommercialComplex::class);
    }

    /**
     * Get the contracts for this shop.
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Get the active contract for this shop.
     */
    public function activeContract()
    {
        return $this->hasOne(Contract::class)->where('status', 'active');
    }

    /**
     * Get current monthly rent.
     */
    public function getCurrentMonthlyRentAttribute(): float
    {
        $activeContract = $this->activeContract;
        return $activeContract ? $activeContract->monthly_rent_with_tax : 0;
    }

    /**
     * Get current company name.
     */
    public function getCurrentCompanyNameAttribute(): ?string
    {
        $activeContract = $this->activeContract;
        return $activeContract ? $activeContract->company->name : null;
    }

    /**
     * Check if shop is occupied.
     */
    public function getIsOccupiedAttribute(): bool
    {
        return $this->status === 'occupied' || $this->activeContract()->exists();
    }
}
