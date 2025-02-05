<?php

namespace App\Http\Controllers;

use App\Models\CartroverIntegration;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartroverIntegrationController extends Controller
{
    public function receive(Request $request, Vendor $vendor, string $auth)
    {
        $decoded_auth = base64_decode(strtr($auth, '-_', '+/'));

        $cartroverIntegration = $vendor->cartroverIntegration()
            ->where('auth', $decoded_auth)
            ->first() ?: false;

        if (!$cartroverIntegration) {
            return response()->json([
                'message' => 'No Integration Configured.',
                'code' => 404
            ], 404);
        }

        if ($request->event == 'connection_test') {

            return response('Connection success');
        } elseif ($request->event == 'on_payment') {

            $cartroverIntegration->processDS24Payload($request->all());
        }
    }
}
