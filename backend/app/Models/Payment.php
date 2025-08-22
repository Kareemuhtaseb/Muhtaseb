<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id','payment_date','amount','method','notes'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
