<?php

use CorexTech\CredoServices\Controllers\CredoController;
use Illuminate\Support\Facades\Route;

// initiate payment
Route::get('/initiate-payment', [CredoController::class, 'initiate'])->name('initiate-payment');

// callback services
Route::get('/payment/call-back', [CredoController::class, 'callback'])->name('payment.callback');

// webhook services
Route::post('/transaction_webhook', [CredoController::class,'transaction_webhook'])->name('transaction_webhook');
