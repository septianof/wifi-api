<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    // Ambil semua data pelanggan
    public function index()
    {
        $pelanggans = Pelanggan::all();
        return response()->json($pelanggans);
    }

    // Tambah pelanggan baru
    public function store(Request $request)
    {
        $pelanggan = Pelanggan::create($request->all());
        return response()->json($pelanggan, 201);
    }

    // Lihat detail pelanggan
    public function show($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return response()->json($pelanggan);
    }

    // Update pelanggan
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update($request->all());
        return response()->json($pelanggan);
    }

    // Soft Delete pelanggan
    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();
        return response()->json(['message' => 'Pelanggan berhasil dihapus (soft delete)']);
    }
}
