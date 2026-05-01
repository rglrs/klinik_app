<?php

namespace App\Http\Controllers\Medis;

use App\Http\Controllers\Controller;
use App\Models\PermintaanObat;
use App\Models\DetailPermintaanObat;
use App\Models\Pegawai;
use App\Models\Penyakit;
use App\Models\Obat;
use App\Models\PresensiTenagaMedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermintaanObatController extends Controller
{
    public function index(Request $request) {
        return $this->getPermintaanData($request, 'medis.minta_obat.index');
    }

    public function scan(Request $request) {
        return $this->getPermintaanData($request, 'medis.minta_obat.scan');
    }

    private function getPermintaanData(Request $request, $view) {
        $tanggal = $request->input('tanggal', now('Asia/Jakarta')->format('Y-m-d'));
        $search = $request->input('search');

        $data = PermintaanObat::with(['pegawai.department', 'pegawai.section', 'tenagaMedis', 'penyakit', 'details.obat'])
            ->when($tanggal, function ($query) use ($tanggal) {
                return $query->whereDate('waktu_permintaan', $tanggal);
            })
            ->when($search, function ($query) use ($search) {
                $searchTerm = '%' . strtolower($search) . '%';
                return $query->whereHas('pegawai', function ($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(name) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(nik) LIKE ?', [$searchTerm]);
                });
            })
            ->latest('waktu_permintaan')
            ->paginate(10)
            ->appends($request->all());

        if ($view === 'medis.minta_obat.index') {
            return view($view, ['permintaan' => $data]);
        }
        return view($view, ['riwayat' => $data]);
    }

    public function processScan(Request $request) {
        $request->validate(['nik' => 'required|exists:pegawai,nik']);
        $pegawai = Pegawai::where('nik', $request->nik)->first();
        return redirect()->route('medis.permintaan-obat.create', ['pegawai_id' => $pegawai->id]);
    }

    public function create(Request $request) {
        if (!$request->has('pegawai_id')) {
            return redirect()->route('medis.permintaan-obat.scan');
        }
        $pegawai = Pegawai::with(['department', 'section'])->findOrFail($request->pegawai_id);
        $penyakit = Penyakit::all();
        $obat = Obat::where('stok_saat_ini', '>', 0)->get();

        return view('medis.minta_obat.form', compact('pegawai', 'penyakit', 'obat'));
    }

    public function store(Request $request) {
        $request->validate([
            'id_pegawai' => 'required|exists:pegawai,id',
            'id_penyakit' => 'required|exists:penyakit,id_penyakit',
            'items' => 'required|array|min:1',
            'items.*.id_obat' => 'required|exists:obat,id_obat',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $activeMedis = PresensiTenagaMedis::whereNull('jam_keluar')->first();
        if (!$activeMedis) {
            return back()->withErrors(['error' => 'Belum ada tenaga medis yang check-in.'])->withInput();
        }

        try {
            DB::beginTransaction();

            $permintaan = PermintaanObat::create([
                'id_pegawai' => $request->id_pegawai,
                'id_tenaga_medis' => $activeMedis->id_tenaga_medis,
                'id_penyakit' => $request->id_penyakit,
            ]);

            foreach ($request->items as $item) {
                $obat = Obat::findOrFail($item['id_obat']);

                if ($obat->stok_saat_ini < $item['qty']) {
                    throw new \Exception("Stok {$obat->nama_obat} tidak mencukupi. Sisa: {$obat->stok_saat_ini}");
                }

                DetailPermintaanObat::create([
                    'id_permintaan' => $permintaan->id_permintaan,
                    'id_obat' => $item['id_obat'],
                    'jumlah_diminta' => $item['qty']
                ]);

                $obat->decrement('stok_saat_ini', $item['qty']);
            }

            DB::commit();
            return redirect()->route('medis.permintaan-obat.scan')->with('success', 'Permintaan obat berhasil diproses & stok dipotong.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}