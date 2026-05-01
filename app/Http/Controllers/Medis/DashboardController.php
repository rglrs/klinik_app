<?php
namespace App\Http\Controllers\Medis;

use App\Http\Controllers\Controller;
use App\Models\PresensiTenagaMedis;
use App\Models\RekamMedis;
use App\Models\Obat;

class DashboardController extends Controller
{
    public function index() {
        $dokterJaga = PresensiTenagaMedis::with('tenagaMedis')->whereNull('jam_keluar')->get();
        $totalPasienHariIni = RekamMedis::whereDate('tanggal_periksa', today())->count();
        $obatKritis = Obat::whereColumn('stok_saat_ini', '<=', 'reorder_level')->get();
        $obatExpired = Obat::where('expired_date', '<=', now()->addDays(30))->get();

        return view('medis.dashboard', compact('dokterJaga', 'totalPasienHariIni', 'obatKritis', 'obatExpired'));
    }
}