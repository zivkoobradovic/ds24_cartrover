<?php

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartroverIntegrationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/integration/cartrover/{vendor:name}/{auth}', [CartroverIntegrationController::class, 'receive'])->name('ipn_url');
