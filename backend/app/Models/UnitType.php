<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    // Accessor methods
    public function getUnitsCountAttribute()
    {
        return $this->units()->count();
    }

    public function getOccupiedUnitsCountAttribute()
    {
        return $this->units()->where('status', 'occupied')->count();
    }

    public function getAvailableUnitsCountAttribute()
    {
        return $this->units()->where('status', 'available')->count();
    }

    public function getOccupancyRateAttribute()
    {
        $totalUnits = $this->units()->count();
        if ($totalUnits === 0) return 0;
        
        $occupiedUnits = $this->units()->where('status', 'occupied')->count();
        return round(($occupiedUnits / $totalUnits) * 100, 2);
    }
}
