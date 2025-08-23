<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'unit_number',
        'unit_type_id',
        'shop_name',
        'shop_number',
        'company_name',
        'monthly_rent_expected',
        'status',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'monthly_rent_expected' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function leases()
    {
        return $this->hasMany(Lease::class);
    }

    public function income()
    {
        return $this->hasMany(Income::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function propertyDocuments()
    {
        return $this->hasMany(PropertyDocument::class);
    }

    // Belongs to relationships
    public function unitType()
    {
        return $this->belongsTo(UnitType::class);
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
    public function getCurrentLeaseAttribute()
    {
        return $this->leases()->where('status', 'active')->first();
    }

    public function getCurrentTenantAttribute()
    {
        return $this->tenants()->whereHas('leases', function($query) {
            $query->where('status', 'active');
        })->first();
    }

    public function getDisplayNameAttribute()
    {
        if ($this->shop_name) {
            return $this->shop_name;
        }
        if ($this->company_name) {
            return $this->company_name;
        }
        return "Unit {$this->unit_number}";
    }

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

    public function getTotalPaymentsAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getOpenMaintenanceRequestsAttribute()
    {
        return $this->maintenanceRequests()->whereIn('status', ['open', 'in_progress'])->count();
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied');
    }

    public function scopeMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    public function scopeWithLeases($query)
    {
        return $query->with(['leases' => function ($q) {
            $q->orderBy('start_date', 'desc');
        }]);
    }

    public function scopeWithTenants($query)
    {
        return $query->with(['tenants', 'currentTenant']);
    }

    public function scopeWithFinancials($query)
    {
        return $query->with(['income', 'expenses', 'payments']);
    }

    public function scopeWithMaintenance($query)
    {
        return $query->with(['maintenanceRequests' => function ($q) {
            $q->orderBy('request_date', 'desc');
        }]);
    }
}
