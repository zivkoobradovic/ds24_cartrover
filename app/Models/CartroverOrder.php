<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartroverOrder extends Model
{
    public function Digistore24Order()
    {
        return $this->belongsTo(Digistore24Order::class, 'order_id', 'digistore_order_id');
    }

    public function CartroverIntegration()
    {
        return $this->hasOne(CartroverIntegration::class, 'id', 'cartrover_integration_id');
    }
}
