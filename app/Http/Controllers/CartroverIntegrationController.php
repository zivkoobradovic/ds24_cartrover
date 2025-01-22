<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartroverIntegrationController extends Controller
{
    public function receive(Request $request, Vendor $vendor, string $auth)
    {
        $decoded_auth = base64_decode(strtr($auth, '-_', '+/'));

        if ($vendor->cartroverIntegration()->where('auth', $decoded_auth)->exists()) {
            $cartroverIntegration = $vendor->cartroverIntegration()->where('auth', $decoded_auth)->first();
            $cartroverIntegration->digistoreOrder()->create($request->all());
        } else {
            echo 'Vendor does not exist';
        }
    }
}
