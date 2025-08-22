<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id','date','amount'
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
