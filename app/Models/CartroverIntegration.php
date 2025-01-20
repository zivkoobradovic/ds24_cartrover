<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartroverIntegration extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'vendor_id',
        'ipn_pass',
        'auth',
        'http_header',
        'ds24_api_key',
        'cr_api_user',
        'cr_api_pass',
        'products',
        'ipn_url',
    ];
    protected $casts = [
        'products' => 'array',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function digistoreOrder()
    {
        return $this->hasMany(Digistore24Order::class, 'order_id', 'digistore_order_id');
    }

    public function cartroverOrder()
    {
        return $this->hasMany(CartroverOrder::class, 'cartrover_integration_id', 'id');
    }

    /**
     * Kreira Digistore24Order unos u bazi na osnovu podataka iz Digistore24 sistema.
     *
     * @param array $data Podaci narudžbine (npr. ID, proizvod, cena, itd.)
     * @return Digistore24Order Kreirani Digistore24Order model.
     */
    public function createDigistoreOrder(array $data)
    {
        return $this->digistore24Order()->create($data);
    }

    /**
     * Komunicira sa Cartrover API-jem i kreira CartroverOrder na osnovu Digistore24Order.
     *
     * @param Digistore24Order $digistoreOrder Digistore24Order model.
     * @param array $apiResponse Response sa Cartrover API-ja.
     * @return CartroverOrder Kreirani CartroverOrder model.
     */
    public function createCartroverOrder(Digistore24Order $digistoreOrder, array $apiResponse)
    {

        //Ovde sledi logika kreiranja Ordera u Cartroverupa na osnovu odgovora sa API-ja
        // kreira se novi unos u bazi podataka za CartroverOrder model

        return $this->cartroverOrder()->create([
            'digistore24_order_id' => $digistoreOrder->id,
            'cartrover_order_id' => $apiResponse['cartrover_order_id'],
            'status' => $apiResponse['status'] ?? 'pending',
        ]);
    }




}
