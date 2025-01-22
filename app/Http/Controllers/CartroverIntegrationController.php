<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartroverIntegrationController extends Controller
{
    public function receive(Request $request, Vendor $vendor, $auth)
    {
        // if ($vendor->cartroverIntegration()->where('auth', $auth)->exists()) {
        //     $cartroverIntegration = $vendor->cartroverIntegration()->where('auth', $auth)->first();
        // return response($request);
        // $digistore24Order = $cartroverIntegration->digistoreOrder()->create($request->all());
        // } else {
        //     echo 'Vendor does not exist';
        // }


        // URL sa kog dolazi zahtev (Referer)
        $referer = $request->headers->get('referer');

        // Trenutni URL na koji dolazi zahtev
        $currentUrl = $request->fullUrl();

        // Logovanje
        Log::info('POST zahtev dolazi sa URL-a: ' . ($referer ?? 'Nepoznato'));
        Log::info('POST zahtev dolazi na URL: ' . $currentUrl);

        // Opcionalno: Logovanje podataka iz tela zahteva
        Log::info('Podaci iz tela zahteva:', $request->all());

        // Povratni odgovor
        return response()->json(['message' => 'POST zahtev je logovan.']);
    }
}
