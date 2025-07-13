<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pelanggan.index');
});

// View untuk index pelanggan
Route::view('/pelanggan', 'pelanggan.index');

// View untuk tambah pelanggan
Route::view('/pelanggan/create', 'pelanggan.create');

// View untuk edit pelanggan
Route::view('/pelanggan/edit', 'pelanggan.edit');