<?php

namespace App\Services;

require_once app_path('Libraries/ds24_api.php');

use App\Libraries\DigistoreApi;
use App\Libraries\DigistoreApiException;


class Digistore24Service
{

    public $api;

    public function connectToAPI($apiKey): DigistoreApi
    {
        return $this->api = DigistoreApi::connect($apiKey);
    }
}
