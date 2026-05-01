<?php

namespace App\Http\Controllers\Medis;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Presensi;
use App\Models\PresensiTenagaMedis;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index(Request $request, $jenis)
    {
        if (!in_array($jenis, ['sakit', 'hamil', 'laktasi'])) abort(404);

        $tanggal = $request->input('tanggal', now('Asia/Jakarta')->format('Y-m-d'));
        $search = $request->input('search');

        $riwayat = Presensi::with(['pegawai.department', 'pegawai.position'])
            ->where('tipe', $jenis)
            ->when($tanggal, function ($query) use ($tanggal) {
                return $query->whereDate('jam_masuk', $tanggal);
            })
            ->when($search, function ($query) use ($search) {
                $searchTerm = '%' . strtolower($search) . '%';
                return $query->whereHas('pegawai', function ($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(name) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(nik) LIKE ?', [$searchTerm]);
                });
            })
            ->latest('jam_masuk')
            ->paginate(10)
            ->appends($request->all());

        $activeMedis = PresensiTenagaMedis::with('tenagaMedis')->whereNull('jam_keluar')->first();

        return view('medis.layanan.index', compact('jenis', 'riwayat', 'activeMedis'));
    }

    public function store(Request $request, $jenis)
    {
        $request->validate(['nik' => 'required|string']);
        $pegawai = Pegawai::where('nik', $request->nik)->first();

        if (!$pegawai) return back()->with('error', 'Pegawai tidak ditemukan.');

        $activeMedis = PresensiTenagaMedis::whereNull('jam_keluar')->first();
        if (!$activeMedis) {
            return back()->with('error', 'Gagal: Belum ada petugas medis (dokter/perawat) yang Check-in.');
        }

        $activeLayanan = Presensi::where('id_pegawai', $pegawai->id)
            ->where('tipe', $jenis)
            ->whereNull('jam_keluar')
            ->first();

        if ($activeLayanan) {
            $activeLayanan->update(['jam_keluar' => now('Asia/Jakarta')]);
            return back()->with('success', 'Check-out ' . ucfirst($jenis) . ' berhasil: ' . $pegawai->name);
        }

        Presensi::create([
            'id_pegawai' => $pegawai->id,
            'tipe' => $jenis,
            'jam_masuk' => now('Asia/Jakarta')
        ]);

        return back()->with('success', 'Check-in ' . ucfirst($jenis) . ' berhasil: ' . $pegawai->name);
    }
}