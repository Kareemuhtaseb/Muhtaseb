<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','share_ratio','email'
    ];

    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }
}
