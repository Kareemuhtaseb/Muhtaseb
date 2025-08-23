<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'date',
        'amount',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
