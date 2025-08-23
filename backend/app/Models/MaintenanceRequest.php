<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'unit_id',
        'tenant_id',
        'reported_by',
        'assigned_to',
        'title',
        'description',
        'priority',
        'status',
        'category',
        'request_date',
        'scheduled_date',
        'completed_date',
        'estimated_cost',
        'actual_cost',
        'notes'
    ];

    protected $casts = [
        'request_date' => 'date',
        'scheduled_date' => 'date',
        'completed_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Accessor methods
    public function getFormattedEstimatedCostAttribute()
    {
        return $this->estimated_cost ? '$' . number_format($this->estimated_cost, 2) : 'N/A';
    }

    public function getFormattedActualCostAttribute()
    {
        return $this->actual_cost ? '$' . number_format($this->actual_cost, 2) : 'N/A';
    }

    public function getPriorityBadgeAttribute()
    {
        return match($this->priority) {
            'urgent' => 'danger',
            'high' => 'warning',
            'medium' => 'info',
            'low' => 'success',
            default => 'secondary'
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'open' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    public function getCategoryIconAttribute()
    {
        return match($this->category) {
            'plumbing' => '🚰',
            'electrical' => '⚡',
            'hvac' => '❄️',
            'structural' => '🏗️',
            'appliance' => '🔧',
            'other' => '📋',
            default => '🔧'
        };
    }

    public function getDaysOpenAttribute()
    {
        if ($this->completed_date) {
            return $this->request_date->diffInDays($this->completed_date);
        }
        return $this->request_date->diffInDays(now());
    }

    public function getIsOverdueAttribute()
    {
        if ($this->scheduled_date && $this->status !== 'completed') {
            return $this->scheduled_date->isPast();
        }
        return false;
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOverdue($query)
    {
        return $query->where('scheduled_date', '<', now())
                    ->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeWithRelations($query)
    {
        return $query->with(['property', 'unit', 'tenant', 'reportedBy', 'assignedTo']);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('request_date', [$startDate, $endDate]);
    }
}
