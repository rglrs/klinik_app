<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Obat;
use App\Models\TenagaMedis;
use App\Models\Penyakit;
use App\Models\RekamMedis;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request) 
    {
        $stats = [
            'pegawai' => Pegawai::count(),
            'obat' => Obat::count(),
            'tenaga_medis' => TenagaMedis::count(),
            'penyakit' => Penyakit::count(),
        ];

        $obatKritis = Obat::whereColumn('stok_saat_ini', '<=', 'reorder_level')->get();
        $obatExpired = Obat::where('expired_date', '<=', now()->addDays(30))->get();

        $filter = $request->query('filter', 'monthly');
        $chartLabels = [];
        $chartData = [];

        if ($filter === 'daily') {
            $startDate = now()->subDays(13)->startOfDay();
            
            $rekamMedis = RekamMedis::where('tanggal_periksa', '>=', $startDate)
                ->selectRaw('CAST(tanggal_periksa AS DATE) as date, count(*) as total')
                ->groupByRaw('CAST(tanggal_periksa AS DATE)')
                ->pluck('total', 'date');

            for ($i = 13; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $chartLabels[] = now()->subDays($i)->translatedFormat('d M');
                $chartData[] = $rekamMedis[$date] ?? 0;
            }
        } elseif ($filter === 'yearly') {
            $startYear = now()->subYears(4)->year;
            
            $rekamMedis = RekamMedis::whereYear('tanggal_periksa', '>=', $startYear)
                ->selectRaw('CAST(EXTRACT(YEAR FROM tanggal_periksa) AS INTEGER) as year, count(*) as total')
                ->groupByRaw('CAST(EXTRACT(YEAR FROM tanggal_periksa) AS INTEGER)')
                ->pluck('total', 'year');

            for ($i = 4; $i >= 0; $i--) {
                $year = now()->subYears($i)->year;
                $chartLabels[] = (string) $year;
                $chartData[] = $rekamMedis[$year] ?? 0;
            }
        } else {
            $currentYear = now()->year;
            
            $rekamMedis = RekamMedis::whereYear('tanggal_periksa', $currentYear)
                ->selectRaw('CAST(EXTRACT(MONTH FROM tanggal_periksa) AS INTEGER) as month, count(*) as total')
                ->groupByRaw('CAST(EXTRACT(MONTH FROM tanggal_periksa) AS INTEGER)')
                ->pluck('total', 'month');

            for ($i = 1; $i <= 12; $i++) {
                $chartLabels[] = Carbon::create()->month($i)->translatedFormat('M');
                $chartData[] = $rekamMedis[$i] ?? 0;
            }
        }

        return view('admin.dashboard', compact('stats', 'obatKritis', 'obatExpired', 'chartLabels', 'chartData', 'filter'));
    }
}