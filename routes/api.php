<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaketController;
use App\Http\Controllers\Api\PelangganController;
use App\Http\Controllers\Api\PembayaranController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route Khusus pelanggan
Route::get('pelanggan/jatuh-tempo', [PelangganController::class, 'pelangganJatuhTempo']);
Route::post('/pelanggan/reset-status', [PelangganController::class, 'resetStatusBayar']);
Route::put('pelanggan/{id}/centang-bayar', [PelangganController::class, 'centangBayar']);

// RESTful pelanggan
Route::apiResource('pelanggan', PelangganController::class);