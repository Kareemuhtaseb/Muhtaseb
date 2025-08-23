<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
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
            'plumbing' => 'faucet',
            'electrical' => 'bolt',
            'hvac' => 'fan',
            'appliance' => 'tv',
            'structural' => 'building',
            'pest_control' => 'bug',
            'cleaning' => 'broom',
            'landscaping' => 'tree',
            'security' => 'shield',
            'other' => 'tools',
            default => 'wrench'
        };
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('scheduled_date', '<', now())
            ->whereIn('status', ['open', 'in_progress']);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeByReportedBy($query, $userId)
    {
        return $query->where('reported_by', $userId);
    }

    public function scopeWithDetails($query)
    {
        return $query->with(['reportedBy', 'assignedTo']);
    }

    // Boot method for automatic timestamps
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($maintenanceRequest) {
            if (!$maintenanceRequest->request_date) {
                $maintenanceRequest->request_date = now();
            }
        });
    }
}
