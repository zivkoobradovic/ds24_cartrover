<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Route::view('integration', 'integration')
//     ->middleware(['auth', 'verified'])
//     ->name('integration');

Route::view('create-cartrover-integration', 'create-cartrover-integration')
    ->middleware(['auth', 'verified'])
    ->name('create-cartrover-integration');

Route::view('create-vendor', 'create-vendor')
    ->middleware(['auth', 'verified'])
    ->name('create-vendor');

Route::view('show-integrations', 'show-integrations')
    ->middleware(['auth', 'verified'])
    ->name('show-integrations');

require __DIR__ . '/auth.php';
