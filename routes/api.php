<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaketController;
use App\Http\Controllers\Api\PelangganController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route RESTful paket
Route::apiResource('pakets', PaketController::class);

// Route RESTful pelanggan
Route::apiResource('pelanggans', PelangganController::class);

// Route RESTful pembayaran
Route::apiResource('pembayarans', PembayaranController::class);
