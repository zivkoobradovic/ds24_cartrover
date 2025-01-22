<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    use HasFactory;
    public $fillable = ['name'];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function cartroverIntegration()
    {
        return $this->hasMany(CartroverIntegration::class);
    }
}
