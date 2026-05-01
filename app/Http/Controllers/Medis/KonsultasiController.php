<?php

namespace App\Http\Controllers\Medis;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\PresensiTenagaMedis;
use App\Models\RekamMedis;
use Illuminate\Http\Request;

class KonsultasiController extends Controller
{
    public function index(Request $request) {
        $tanggal = $request->input('tanggal', now('Asia/Jakarta')->format('Y-m-d'));
        $search = $request->input('search');

        $riwayat = RekamMedis::with(['pegawai', 'tenagaMedis'])
            ->when($tanggal, function ($query) use ($tanggal) {
                return $query->whereDate('tanggal_periksa', $tanggal);
            })
            ->when($search, function ($query) use ($search) {
                $searchTerm = '%' . strtolower($search) . '%';
                return $query->whereHas('pegawai', function ($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(name) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(nik) LIKE ?', [$searchTerm]);
                });
            })
            ->latest('tanggal_periksa')
            ->paginate(10)
            ->appends($request->all());

        return view('medis.konsultasi.scan', compact('riwayat'));
    }

    public function processScan(Request $request) {
        $request->validate(['nik' => 'required|string']);
        $pegawai = Pegawai::where('nik', $request->nik)->first();
        
        if (!$pegawai) return back()->withErrors(['nik' => 'Pegawai tidak ditemukan.']);
        
        $activeMedis = PresensiTenagaMedis::whereNull('jam_keluar')->first();
        if (!$activeMedis) {
            return back()->withErrors(['error' => 'Belum ada tenaga medis yang check-in.']);
        }

        RekamMedis::create([
            'id_pegawai' => $pegawai->id,
            'id_tenaga_medis' => $activeMedis->id_tenaga_medis,
            'keluhan' => 'Konsultasi Medis',
            'diagnosa' => '-',
            'tanggal_periksa' => now(),
        ]);
        
        return back()->with('success', 'Check-in Konsultasi berhasil untuk: ' . $pegawai->name);
    }
}