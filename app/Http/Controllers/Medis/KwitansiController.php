<?php

namespace App\Http\Controllers\Medis;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\PresensiTenagaMedis;
use App\Models\RekamMedis;
use App\Models\Kwitansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KwitansiController extends Controller
{
    public function index(Request $request) {
        return $this->getKwitansiData($request, 'medis.kwitansi.index');
    }

    public function scan(Request $request) {
        return $this->getKwitansiData($request, 'medis.kwitansi.scan');
    }

    private function getKwitansiData(Request $request, $view) {
        $tanggal = $request->input('tanggal', now('Asia/Jakarta')->format('Y-m-d'));
        $search = $request->input('search');

        $riwayat = Kwitansi::with(['rekamMedis.pegawai.department', 'rekamMedis.pegawai.section', 'rekamMedis.tenagaMedis'])
            ->when($tanggal, function ($query) use ($tanggal) {
                return $query->whereDate('tanggal_terbit', $tanggal);
            })
            ->when($search, function ($query) use ($search) {
                $searchTerm = '%' . strtolower($search) . '%';
                return $query->whereHas('rekamMedis.pegawai', function ($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(name) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(nik) LIKE ?', [$searchTerm]);
                });
            })
            ->latest('tanggal_terbit')
            ->paginate(10)
            ->appends($request->all());

        return view($view, compact('riwayat'));
    }

    public function processScan(Request $request) {
        $request->validate(['nik' => 'required|exists:pegawai,nik']);
        $pegawai = Pegawai::where('nik', $request->nik)->firstOrFail();

        return redirect()->route('medis.kwitansi.create', ['pegawai_id' => $pegawai->id]);
    }

    public function create(Request $request) {
        if (!$request->has('pegawai_id')) {
            return redirect()->route('medis.kwitansi.scan');
        }
        $pegawai = Pegawai::with(['department', 'section'])->findOrFail($request->pegawai_id);
        return view('medis.kwitansi.form', compact('pegawai'));
    }

    public function store(Request $request) {
        $request->validate([
            'id_pegawai' => 'required|exists:pegawai,id',
            'status_perawatan' => 'required|in:rawat_jalan,rawat_inap'
        ]);

        $activeMedis = PresensiTenagaMedis::whereNull('jam_keluar')->first();
        if (!$activeMedis) {
            return back()->withErrors(['error' => 'Belum ada tenaga medis yang check-in.'])->withInput();
        }

        try {
            DB::beginTransaction();

            $rekamMedis = RekamMedis::create([
                'id_pegawai' => $request->id_pegawai,
                'id_tenaga_medis' => $activeMedis->id_tenaga_medis,
                'keluhan' => 'Penyerahan Kwitansi',
                'diagnosa' => '-',
                'status_perawatan' => $request->status_perawatan,
                'tanggal_periksa' => now(),
            ]);

            Kwitansi::create([
                'id_rekam_medis' => $rekamMedis->id_rekam_medis,
                'total_biaya' => 0,
                'status' => 'belum_lunas',
                'tanggal_terbit' => now(),
            ]);

            DB::commit();
            return redirect()->route('medis.kwitansi.scan')->with('success', 'Data rujukan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}