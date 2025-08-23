<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $table = 'tenants';

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
        'deposit' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}


