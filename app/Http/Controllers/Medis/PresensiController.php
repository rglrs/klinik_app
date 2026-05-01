<?php

namespace App\Http\Controllers\Medis;

use App\Http\Controllers\Controller;
use App\Models\TenagaMedis;
use App\Models\PresensiTenagaMedis;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function index(Request $request) {
        $tanggal = $request->input('tanggal', now('Asia/Jakarta')->format('Y-m-d'));
        $search = $request->input('search');

        $riwayat = PresensiTenagaMedis::with('tenagaMedis')
            ->when($tanggal, function ($query) use ($tanggal) {
                return $query->whereDate('jam_masuk', $tanggal);
            })
            ->when($search, function ($query) use ($search) {
                $searchTerm = '%' . strtolower($search) . '%';
                return $query->whereHas('tenagaMedis', function ($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(nama_tenaga_medis) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(kode_tenaga_medis) LIKE ?', [$searchTerm]);
                });
            })
            ->latest('jam_masuk')
            ->paginate(10)
            ->appends($request->all());

        return view('medis.presensi.index', compact('riwayat'));
    }

    public function store(Request $request) {
        $request->validate(['nik' => 'required|string']);
        $tenagaMedis = TenagaMedis::where('nik', $request->nik)->first();
        
        if (!$tenagaMedis) {
            return back()->withErrors(['nik' => 'Data Tenaga Medis tidak ditemukan.']);
        }

        $activePresensi = PresensiTenagaMedis::where('id_tenaga_medis', $tenagaMedis->id_tenaga_medis)
            ->whereNull('jam_keluar')
            ->first();

        if ($activePresensi) {
            $activePresensi->update(['jam_keluar' => now()]);
            return back()->with('success', 'Check-Out Berhasil: ' . $tenagaMedis->nama_tenaga_medis);
        } else {
            PresensiTenagaMedis::create([
                'id_tenaga_medis' => $tenagaMedis->id_tenaga_medis,
                'keterangan' => 'hadir'
            ]);
            return back()->with('success', 'Check-In Berhasil: ' . $tenagaMedis->nama_tenaga_medis);
        }
    }
}