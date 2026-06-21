<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Obat;
use App\Models\TenagaMedis;
use App\Models\Penyakit;
use App\Models\RekamMedis;
use App\Models\Presensi;
use App\Models\PermintaanObat;
use App\Models\Kwitansi;
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
            
            $rekamMedis = $this->getStats(RekamMedis::class, 'tanggal_periksa', $startDate, 'daily');
            $presensi = $this->getStats(Presensi::class, 'jam_masuk', $startDate, 'daily');
            $permintaanObat = $this->getStats(PermintaanObat::class, 'waktu_permintaan', $startDate, 'daily');
            $kwitansi = $this->getStats(Kwitansi::class, 'tanggal_terbit', $startDate, 'daily');

            for ($i = 13; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $chartLabels[] = now()->subDays($i)->translatedFormat('d M');
                $chartData[] = ($rekamMedis[$date] ?? 0) + ($presensi[$date] ?? 0) + ($permintaanObat[$date] ?? 0) + ($kwitansi[$date] ?? 0);
            }
        } elseif ($filter === 'yearly') {
            $startYear = now()->subYears(4)->year;
            
            $rekamMedis = $this->getStats(RekamMedis::class, 'tanggal_periksa', $startYear, 'yearly');
            $presensi = $this->getStats(Presensi::class, 'jam_masuk', $startYear, 'yearly');
            $permintaanObat = $this->getStats(PermintaanObat::class, 'waktu_permintaan', $startYear, 'yearly');
            $kwitansi = $this->getStats(Kwitansi::class, 'tanggal_terbit', $startYear, 'yearly');

            for ($i = 4; $i >= 0; $i--) {
                $year = now()->subYears($i)->year;
                $chartLabels[] = (string) $year;
                $chartData[] = ($rekamMedis[$year] ?? 0) + ($presensi[$year] ?? 0) + ($permintaanObat[$year] ?? 0) + ($kwitansi[$year] ?? 0);
            }
        } else {
            $currentYear = now()->year;
            
            $rekamMedis = $this->getStats(RekamMedis::class, 'tanggal_periksa', $currentYear, 'monthly');
            $presensi = $this->getStats(Presensi::class, 'jam_masuk', $currentYear, 'monthly');
            $permintaanObat = $this->getStats(PermintaanObat::class, 'waktu_permintaan', $currentYear, 'monthly');
            $kwitansi = $this->getStats(Kwitansi::class, 'tanggal_terbit', $currentYear, 'monthly');

            for ($i = 1; $i <= 12; $i++) {
                $chartLabels[] = Carbon::create()->month($i)->translatedFormat('M');
                $chartData[] = ($rekamMedis[$i] ?? 0) + ($presensi[$i] ?? 0) + ($permintaanObat[$i] ?? 0) + ($kwitansi[$i] ?? 0);
            }
        }

        return view('admin.dashboard', compact('stats', 'obatKritis', 'obatExpired', 'chartLabels', 'chartData', 'filter'));
    }

    private function getStats($model, $column, $param, $type)
    {
        $query = $model::query();

        if ($type === 'daily') {
            return $query->where($column, '>=', $param)
                ->selectRaw("CAST({$column} AS DATE) as date, count(*) as total")
                ->groupByRaw("CAST({$column} AS DATE)")
                ->pluck('total', 'date');
        }

        if ($type === 'yearly') {
            return $query->whereYear($column, '>=', $param)
                ->selectRaw("CAST(EXTRACT(YEAR FROM {$column}) AS INTEGER) as year, count(*) as total")
                ->groupByRaw("CAST(EXTRACT(YEAR FROM {$column}) AS INTEGER)")
                ->pluck('total', 'year');
        }

        if ($type === 'monthly') {
            return $query->whereYear($column, $param)
                ->selectRaw("CAST(EXTRACT(MONTH FROM {$column}) AS INTEGER) as month, count(*) as total")
                ->groupByRaw("CAST(EXTRACT(MONTH FROM {$column}) AS INTEGER)")
                ->pluck('total', 'month');
        }

        return collect();
    }
}