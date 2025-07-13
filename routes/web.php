<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// View untuk index pelanggan
Route::view('/pelanggans', 'pelanggans.index');

// View untuk tambah pelanggan
Route::view('/pelanggans/create', 'pelanggans.create');

// View untuk edit pelanggan
Route::view('/pelanggans/edit', 'pelanggans.edit');