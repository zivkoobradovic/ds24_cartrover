<?php

namespace App\Services\Digistore24;

use App\Models\CartroverIntegration;

class digistore24OrderService
{

    protected $ds24Payload;

    public function __construct($ds24Payload)
    {
        $this->ds24Payload = $ds24Payload;
    }

    public function extractProductDetails(): array
    {
        $products = [];
        $order_id = $this->ds24Payload['order_id'];
        $itemCount = $this->ds24Payload['item_count'] ?? 0;

        for ($i = 0; $i < $itemCount; $i++) {
            $suffix = $i === 0 ? '' : "_" . ($i + 1);

            $productId = $this->ds24Payload["product_id$suffix"] ?? null;
            $productName = $this->ds24Payload["product_name$suffix"] ?? null;
            $quantity = $this->ds24Payload["quantity$suffix"] ?? null;
            $item_order_id = $order_id . ($i === 0 ? '' : $i);

            if ($productId) {
                $products[$productId] = [
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'quantity' => $quantity,
                    'item_order_id' => $item_order_id,
                ];
            }
        }

        return $products;
    }

    /**
     * Extract fields containing "address_" into an array.
     *
     * @return array
     */
    public function extractAddressFields(): array
    {
        $addressFields = [];

        foreach ($this->ds24Payload as $key => $value) {
            if (str_starts_with($key, 'address_')) {
                $addressFields[$key] = $value;
            }
        }

        return $addressFields;
    }

    /**
     * Extract fields containing "buyer_" into an array.
     *
     * @return array
     */
    public function extractBuyerFields(): array
    {
        $buyerFields = [];

        foreach ($this->ds24Payload as $key => $value) {
            if (str_starts_with($key, 'buyer_')) {
                $buyerFields[$key] = $value;
            }
        }

        return $buyerFields;
    }

    public function getOrderDate() {
        return $this->ds24Payload['order_date'];
    }

    public function getOrderTime()
    {
        return $this->ds24Payload['order_time'];
    }

    public function getPayMethod(){
        return $this->ds24Payload['pay_method'];
    }

    public function getFullDigistoreJsonOrder() {
        return json_encode($this->ds24Payload);
    }

    public function getDigistoreInitialOrderId() {
        return $this->ds24Payload['order_id'];
    }

    public function getBuyerEmailAddress() {
        return $this->ds24Payload['buyer_email'];
    }

    public function DS24OrderHandler(CartroverIntegration $cartroverIntegration)
    {
        $addressFields = $this->extractAddressFields();
        $buyerFields = $this->extractBuyerFields();
        $product_details = $this->extractProductDetails();
        $order_id = $this->getDigistoreInitialOrderId();
        // return [
        //     'cartrover_integration_id' => $cartroverIntegration->id,
        //     'order_id' => $this->getDigistoreInitialOrderId(),
        //     'product_details' => $product_details,
        //     'buyer_first_name' => $buyerFields['buyer_first_name'],
        //     'buyer_last_name' => $buyerFields['buyer_last_name'],
        //     'buyer_email' => $this->getBuyerEmailAddress(),
        //     'buyer_address_street' => $buyerFields['buyer_address_street'],
        //     'buyer_address_street2' => $buyerFields['buyer_address_street2'],
        //     'buyer_address_phone_no' => $buyerFields['buyer_address_phone_no'],
        //     'buyer_address_company' => $buyerFields['buyer_address_company'],
        //     'buyer_address_city' => $buyerFields['buyer_address_city'],
        //     'buyer_address_zipcode' => $buyerFields['buyer_address_zipcode'],
        //     'buyer_address_state' => $buyerFields['buyer_address_state'],
        //     'buyer_address_country' => $buyerFields['buyer_address_country'],
        //     'address_first_name' => $addressFields['address_first_name'],
        //     'address_last_name' => $addressFields['address_last_name'],
        //     'order_date' => $this->getOrderDate(),
        //     'order_time' => $this->getOrderTime(),
        //     'pay_method' => $this->getPayMethod(),
        //     'json_order' => $this->getFullDigistoreJsonOrder(),
        // ];

        return $cartroverIntegration->digistoreOrder()->create([
            'cartrover_integration_id' => $cartroverIntegration->id,
            'order_id' => $order_id,
            'product_details' => json_encode($product_details),
            'buyer_first_name' => $buyerFields['buyer_first_name'],
            'buyer_last_name' => $buyerFields['buyer_last_name'],
            'buyer_email' => $this->getBuyerEmailAddress(),
            'buyer_address_street' => $buyerFields['buyer_address_street'],
            'buyer_address_street2' => $buyerFields['buyer_address_street2'],
            'buyer_address_phone_no' => $buyerFields['buyer_address_phone_no'],
            'buyer_address_company' => $buyerFields['buyer_address_company'],
            'buyer_address_city' => $buyerFields['buyer_address_city'],
            'buyer_address_zipcode' => $buyerFields['buyer_address_zipcode'],
            'buyer_address_state' => $buyerFields['buyer_address_state'],
            'buyer_address_country' => $buyerFields['buyer_address_country'],
            'address_first_name' => $addressFields['address_first_name'],
            'address_last_name' => $addressFields['address_last_name'],
            'order_date' => $this->getOrderDate(),
            'order_time' => $this->getOrderTime(),
            'pay_method' => $this->getPayMethod(),
            'json_order' => json_encode($this->getFullDigistoreJsonOrder()),
        ]);
    }

}
