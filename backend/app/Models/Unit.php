<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_number','size','monthly_rent','status'
    ];

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}
