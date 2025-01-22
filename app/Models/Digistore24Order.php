<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Digistore24Order extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function CartroverIntegration()
    {
        return $this->belongsTo(CartroverIntegration::class, 'digistore_order_id', 'order_id');
    }

    public function CartroverOrder()
    {
        return $this->hasOne(CartroverOrder::class, 'id', 'order_id');
    }
}
