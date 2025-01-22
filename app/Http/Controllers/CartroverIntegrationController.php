<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartroverIntegrationController extends Controller
{
    public function receive(Request $request, Vendor $vendor, string $auth)
    {
        if($vendor->cartroverIntegration()->where('auth', $auth)->exists()) {
                $cartroverIntegration = $vendor->cartroverIntegration()->where('auth', $auth)->first();
            $cartroverIntegration->digistoreOrder()->create($request->all());
        } else {
           echo 'Vendor does not exist';

        }
    }
}
