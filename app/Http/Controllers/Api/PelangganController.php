<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    // Ambil semua data pelanggan + fitur search + sort
    public function index(Request $request)
    {
        $query = Pelanggan::query();

        // Fitur search by nama
        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Urut A-Z
        $query->orderBy('nama', 'asc');

        // Pagination (10 per halaman)
        $pelanggans = $query->paginate(10);

        return response()->json($pelanggans);
    }

    // Tambah pelanggan baru (AUTO jatuh tempo tgl 25)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'no_hp' => 'nullable',
            'tagihan' => 'required|integer',
        ]);

        $tanggalSekarang = now();
        $tanggalJatuhTempo = $tanggalSekarang->copy()->day(25);
        if ($tanggalSekarang->day > 25) {
            $tanggalJatuhTempo->addMonth();
        }

        $pelanggan = Pelanggan::create(array_merge($validated, [
            'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
            'status_bayar' => false,
        ]));

        return response()->json($pelanggan, 201);
    }

    // Lihat detail pelanggan
    public function show($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return response()->json($pelanggan);
    }

    // Update data pelanggan
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'sometimes|required',
            'alamat' => 'sometimes|required',
            'no_hp' => 'nullable',
            'tagihan' => 'sometimes|required|integer',
        ]);

        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update($validated);

        return response()->json($pelanggan);
    }

    // Hapus pelanggan (soft delete)
    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();
        return response()->json(['message' => 'Pelanggan berhasil dihapus']);
    }

    // Centang bayar (ubah status bayar)
    public function centangBayar($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update(['status_bayar' => true]);
        return response()->json(['message' => 'Status bayar diubah menjadi LUNAS']);
    }

    // Reset Status Bayar semua pelanggan
    public function resetStatusBayar()
    {
        $pelanggans = Pelanggan::all();

        foreach ($pelanggans as $pelanggan) {
            // Cek tanggal jatuh tempo pelanggan
            $jatuhTempo = \Carbon\Carbon::parse($pelanggan->tanggal_jatuh_tempo);
            $hariIni = now();

            // Kalau tanggal jatuh tempo sudah lewat bulan ini, update ke bulan berikutnya
            if ($jatuhTempo->lt($hariIni)) {
                $jatuhTempo->addMonthNoOverflow()->day(25); // ganti tgl 25 sesuai sistem kamu
            }

            $pelanggan->update([
                'status_bayar' => false,
                'tanggal_jatuh_tempo' => $jatuhTempo
            ]);
        }

        return response()->json(['message' => 'Status bayar & tanggal jatuh tempo telah diperbarui.']);
    }

    // Menampilkan pelanggan yang sudah melewati tanggal jatuh tempo & belum bayar
    public function pelangganJatuhTempo()
    {
        $today = now()->startOfDay();

        $pelanggans = Pelanggan::where('status_bayar', false)
            ->whereDate('tanggal_jatuh_tempo', '<=', $today)
            ->get();

        return response()->json($pelanggans);
    }
}
