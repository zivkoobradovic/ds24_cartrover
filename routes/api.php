<?php

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartroverIntegrationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/integration/cartrover/{vendor}/{auth}', [CartroverIntegrationController::class, 'receive'])->name('ipn_url');

// Route::post('/integration/cartrover/{vendor}/{auth}', function (Request $request, $vendor, $auth) {


//     $vendor = Vendor::where('name', $vendor)->whereHas('cartroverIntegration', function ($query) use ($auth) {
//         $query->where('auth', $auth);
//     })->with(['cartroverIntegrations' => function ($query) use ($auth) {
//         $query->where('auth', $auth);
//     }])->first();
//     $response = [
//         'vendor' => $vendor,
//         'request' => $request->all(),
//     ];
//     return json_encode($response, JSON_PRETTY_PRINT);
// })->name('ipn_url');



// Route::post('/integration/cartrover/', function (Request $request) {
//    echo 'No vendor or auth provided.';
// });
