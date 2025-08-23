<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_id',
        'tenant_id',
        'owner_id',
        'type',
        'title',
        'message',
        'priority',
        'status',
        'data',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    // Accessor methods
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
            'unread' => 'warning',
            'read' => 'success',
            'archived' => 'secondary',
            default => 'secondary'
        };
    }

    public function getIsReadAttribute()
    {
        return !is_null($this->read_at);
    }

    public function getIsUnreadAttribute()
    {
        return is_null($this->read_at);
    }

    public function getTypeIconAttribute()
    {
        return match($this->type) {
            'lease_expiry' => '📅',
            'payment_due' => '💰',
            'maintenance' => '🔧',
            'document_expiry' => '📄',
            'overdue_payment' => '⚠️',
            'new_tenant' => '👤',
            'property_update' => '🏠',
            'financial_report' => '📊',
            default => '🔔'
        };
    }

    public function getShortMessageAttribute()
    {
        return strlen($this->message) > 100 ? substr($this->message, 0, 100) . '...' : $this->message;
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForProperty($query, $propertyId)
    {
        return $query->where('property_id', $propertyId);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeForOwner($query, $ownerId)
    {
        return $query->where('owner_id', $ownerId);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeWithRelations($query)
    {
        return $query->with(['user', 'property', 'tenant', 'owner']);
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'status' => 'read',
            'read_at' => now()
        ]);
    }

    public function markAsUnread()
    {
        $this->update([
            'status' => 'unread',
            'read_at' => null
        ]);
    }

    public function archive()
    {
        $this->update(['status' => 'archived']);
    }
}
