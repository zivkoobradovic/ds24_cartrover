<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;

class CartroverIntegration extends Model
{
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


    public function sendToCartrover(Digistore24Order $digistoreOrder)
    {
        // Priprema podataka za Cartrover API
        $payload = [
            'cust_ref'               => $digistoreOrder->order_id,
            'ship_first_name'        => $digistoreOrder->buyer_first_name,
            'ship_last_name'         => $digistoreOrder->buyer_last_name,
            'ship_address_1'         => $digistoreOrder->buyer_address_street1,
            'ship_address_2'         => $digistoreOrder->buyer_address_street2,
            'ship_phone'             => $digistoreOrder->buyer_phone,
            'ship_company'           => $digistoreOrder->buyer_company,
            'ship_city'              => $digistoreOrder->buyer_address_city,
            'ship_zip'               => $digistoreOrder->buyer_address_zip,
            'ship_state'             => $digistoreOrder->buyer_address_state,
            'ship_country'           => $digistoreOrder->buyer_address_country,
            'cust_first_name'        => $digistoreOrder->buyer_first_name,
            'cust_last_name'         => $digistoreOrder->buyer_last_name,
            'items'                  => [
                'item' => $product_sku[posted_value('product_id')],
                'quantity' => intval(posted_value('quantity')),
            ],
        ];

        // Dodavanje API ključa i drugih podataka iz trenutne instance
        $apiUrl = env('CREATE_ORDER_CARTROVER_API_URL');

        $cr_api_user_api_key = base64_encode($this->cr_api_user . ":" . $this->cr_api_pass);

        try {
            // Slanje API zahteva pomoću Guzzle HTTP klijenta ili Laravel HTTP wrappera

            // $response = Http::retry(3, 100)->withHeaders([...])->post($apiUrl, $payload);

            $response = Http::withHeaders([
                'Authorization' => "Bearer $cr_api_user_api_key", // Autorizacija pomoću API ključa
                'Content-Type' => 'application/json',
            ])->post($apiUrl, $payload);

            // Obrada odgovora
            if ($response->successful()) {
                // Kreiranje CartroverOrder unosa na osnovu odgovora API-ja
                $this->createCartroverOrder($digistoreOrder, $response->json());
            } else {
                // Logovanje greške
                Log::error('Cartrover API error', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            // Obrada izuzetaka (ako dođe do problema u komunikaciji sa API-jem)
            Log::error('Cartrover API exception', [
                'message' => $e->getMessage(),
                'order_id' => $digistoreOrder->order_id,
            ]);
        }
    }

}
