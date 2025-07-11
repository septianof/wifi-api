<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    // Ambil semua data paket
    public function index()
    {
        $pakets = Paket::all();
        return response()->json($pakets);
    }

    // Tambah paket baru
    public function store(Request $request)
    {
        $paket = Paket::create($request->all());
        return response()->json($paket, 201);
    }

    // Lihat detail paket
    public function show($id)
    {
        $paket = Paket::findOrFail($id);
        return response()->json($paket);
    }

    // Update paket
    public function update(Request $request, $id)
    {
        $paket = Paket::findOrFail($id);
        $paket->update($request->all());
        return response()->json($paket);
    }

    // Soft Delete paket
    public function destroy($id)
    {
        $paket = Paket::findOrFail($id);
        $paket->delete();
        return response()->json(['message' => 'Paket berhasil dihapus (soft delete)']);
    }
}
