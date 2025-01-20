<?php

namespace App\Services\Cartrover;

use App\Models\Digistore24Order;

class CartroverService
{
    // public function createCartroverOrder(Digistore24Order $digistoreOrder, array $apiResponse)
    // {
    //     return $this->cartroverOrder()->create($apiResponse);
    // }


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
