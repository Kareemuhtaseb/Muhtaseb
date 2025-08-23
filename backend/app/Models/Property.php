<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'property_type_id',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function income()
    {
        return $this->hasMany(Income::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function monthlySummaries()
    {
        return $this->hasMany(MonthlySummary::class);
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function propertyDocuments()
    {
        return $this->hasMany(PropertyDocument::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function ownerDistributions()
    {
        return $this->hasMany(OwnerDistribution::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    // Many-to-many relationships
    public function owners()
    {
        return $this->belongsToMany(Owner::class, 'property_owner')
                    ->withPivot('ownership_percentage', 'ownership_start_date', 'ownership_end_date', 'status', 'notes')
                    ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_property_access')
                    ->withPivot('access_level', 'access_start_date', 'access_end_date', 'status', 'notes')
                    ->withTimestamps();
    }

    // Belongs to relationships
    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Accessor methods
    public function getTotalIncomeAttribute()
    {
        return $this->income()->sum('amount');
    }

    public function getTotalExpensesAttribute()
    {
        return $this->expenses()->sum('amount');
    }

    public function getNetIncomeAttribute()
    {
        return $this->total_income - $this->total_expenses;
    }

    public function getOccupancyRateAttribute()
    {
        $totalUnits = $this->units()->count();
        if ($totalUnits === 0) return 0;
        
        $occupiedUnits = $this->units()->where('status', 'occupied')->count();
        return round(($occupiedUnits / $totalUnits) * 100, 2);
    }

    public function getTotalUnitsAttribute()
    {
        return $this->units()->count();
    }

    public function getOccupiedUnitsAttribute()
    {
        return $this->units()->where('status', 'occupied')->count();
    }

    public function getAvailableUnitsAttribute()
    {
        return $this->units()->where('status', 'available')->count();
    }

    public function getMaintenanceUnitsAttribute()
    {
        return $this->units()->where('status', 'maintenance')->count();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereHas('units', function ($q) {
            $q->where('status', '!=', 'inactive');
        });
    }

    public function scopeWithUnits($query)
    {
        return $query->with(['units', 'units.leases', 'units.leases.tenant']);
    }

    public function scopeWithFinancials($query)
    {
        return $query->with(['income', 'expenses', 'monthlySummaries']);
    }

    public function scopeWithOwners($query)
    {
        return $query->with(['owners' => function ($q) {
            $q->where('status', 'active');
        }]);
    }
}
