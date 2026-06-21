<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\PresensiTenagaMedis;
use App\Models\RekamMedis;
use App\Models\PermintaanObat;
use App\Models\Kwitansi;
use App\Exports\ReportExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    private function applyDateFilter($query, $request, $dateColumn = 'created_at')
    {
        if ($request->start_date && $request->end_date) {
            $query->whereBetween($dateColumn, [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }
        return $query;
    }

    private function getPresensiBaseQuery(Request $request, $tipe)
    {
        $query = Presensi::with('pegawai')->where('tipe', $tipe)->latest();
        
        if ($request->search) {
            $query->whereHas('pegawai', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }
        
        return $this->applyDateFilter($query, $request);
    }

    private function generateExcelPresensi($query, $filename, $title)
    {
        $data = $query->get()->map(function ($item) {
            return [
                'Tanggal' => $item->created_at->format('Y-m-d H:i:s'),
                'NIK' => $item->pegawai->nik,
                'Nama Pegawai' => $item->pegawai->name,
                'Tipe' => ucfirst($item->tipe),
                'Jam Masuk' => $item->jam_masuk,
                'Jam Keluar' => $item->jam_keluar ?? '-',
            ];
        });

        return Excel::download(new ReportExport($data, ['Tanggal', 'NIK', 'Nama Pegawai', 'Tipe', 'Jam Masuk', 'Jam Keluar']), $filename);
    }

    public function presensiTenagaMedis(Request $request)
    {
        $query = PresensiTenagaMedis::with('tenagaMedis')->latest();
        
        if ($request->search) {
            $query->whereHas('tenagaMedis', function ($q) use ($request) {
                $q->where('nama_tenaga_medis', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->keterangan) {
            $query->where('keterangan', $request->keterangan);
        }
        
        $query = $this->applyDateFilter($query, $request);
        $data = $query->paginate(10)->appends($request->all());
        
        return view('admin.laporan.presensi-tenaga-medis', compact('data'));
    }

    public function exportPresensiTenagaMedis(Request $request)
    {
        $query = PresensiTenagaMedis::with('tenagaMedis')->latest();
        
        if ($request->search) {
            $query->whereHas('tenagaMedis', function ($q) use ($request) {
                $q->where('nama_tenaga_medis', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->keterangan) {
            $query->where('keterangan', $request->keterangan);
        }
        
        $query = $this->applyDateFilter($query, $request);
        
        $data = $query->get()->map(function ($item) {
            return [
                'Tanggal' => $item->created_at->format('Y-m-d H:i:s'),
                'NIK' => $item->tenagaMedis->nik,
                'Nama Tenaga Medis' => $item->tenagaMedis->nama_tenaga_medis,
                'Keterangan' => ucfirst($item->keterangan),
                'Jam Masuk' => $item->jam_masuk,
                'Jam Keluar' => $item->jam_keluar ?? '-',
            ];
        });

        return Excel::download(new ReportExport($data, ['Tanggal', 'NIK', 'Nama Tenaga Medis', 'Keterangan', 'Jam Masuk', 'Jam Keluar']), 'Laporan_Presensi_Tenaga_Medis.xlsx');
    }

    public function istirahatSakit(Request $request)
    {
        $data = $this->getPresensiBaseQuery($request, 'sakit')->paginate(10)->appends($request->all());
        return view('admin.laporan.istirahat-sakit', compact('data'));
    }

    public function exportIstirahatSakit(Request $request)
    {
        $query = $this->getPresensiBaseQuery($request, 'sakit');
        return $this->generateExcelPresensi($query, 'Laporan_Istirahat_Sakit.xlsx', 'Istirahat Sakit');
    }

    public function istirahatHamil(Request $request)
    {
        $data = $this->getPresensiBaseQuery($request, 'hamil')->paginate(10)->appends($request->all());
        return view('admin.laporan.istirahat-hamil', compact('data'));
    }

    public function exportIstirahatHamil(Request $request)
    {
        $query = $this->getPresensiBaseQuery($request, 'hamil');
        return $this->generateExcelPresensi($query, 'Laporan_Istirahat_Hamil.xlsx', 'Istirahat Hamil');
    }

    public function laktasi(Request $request)
    {
        $data = $this->getPresensiBaseQuery($request, 'laktasi')->paginate(10)->appends($request->all());
        return view('admin.laporan.laktasi', compact('data'));
    }

    public function exportLaktasi(Request $request)
    {
        $query = $this->getPresensiBaseQuery($request, 'laktasi');
        return $this->generateExcelPresensi($query, 'Laporan_Laktasi.xlsx', 'Laktasi');
    }

    public function konsultasi(Request $request)
    {
        $query = RekamMedis::with(['pegawai', 'tenagaMedis'])->latest('tanggal_periksa');
        
        if ($request->search) {
            $query->whereHas('pegawai', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }
        
        $query = $this->applyDateFilter($query, $request, 'tanggal_periksa');
        $data = $query->paginate(10)->appends($request->all());
        
        return view('admin.laporan.konsultasi', compact('data'));
    }

    public function exportKonsultasi(Request $request)
    {
        $query = RekamMedis::with(['pegawai', 'tenagaMedis'])->latest('tanggal_periksa');
        
        if ($request->search) {
            $query->whereHas('pegawai', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }
        
        $query = $this->applyDateFilter($query, $request, 'tanggal_periksa');
        
        $data = $query->get()->map(function ($item) {
            return [
                'Tanggal Periksa' => $item->tanggal_periksa,
                'NIK' => $item->pegawai->nik,
                'Nama Pasien' => $item->pegawai->name,
                'Tenaga Medis' => $item->tenagaMedis->nama_tenaga_medis,
                'Keluhan' => $item->keluhan,
                'Diagnosa' => $item->diagnosa,
                'Tindakan' => $item->tindakan ?? '-',
                'Status Perawatan' => str_replace('_', ' ', ucfirst($item->status_perawatan)),
            ];
        });

        return Excel::download(new ReportExport($data, ['Tanggal Periksa', 'NIK', 'Nama Pasien', 'Tenaga Medis', 'Keluhan', 'Diagnosa', 'Tindakan', 'Status Perawatan']), 'Laporan_Konsultasi_Medis.xlsx');
    }

    public function permintaanObat(Request $request)
    {
        $query = PermintaanObat::with(['pegawai', 'tenagaMedis', 'penyakit', 'details'])->latest('waktu_permintaan');
        
        if ($request->search) {
            $query->whereHas('pegawai', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }
        
        $query = $this->applyDateFilter($query, $request, 'waktu_permintaan');
        $data = $query->paginate(10)->appends($request->all());
        
        return view('admin.laporan.permintaan-obat', compact('data'));
    }

    public function exportPermintaanObat(Request $request)
    {
        $query = PermintaanObat::with(['pegawai', 'tenagaMedis', 'penyakit', 'details'])->latest('waktu_permintaan');
        
        if ($request->search) {
            $query->whereHas('pegawai', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }
        
        $query = $this->applyDateFilter($query, $request, 'waktu_permintaan');
        
        $data = $query->get()->map(function ($item) {
            return [
                'Waktu Permintaan' => $item->waktu_permintaan,
                'NIK' => $item->pegawai->nik,
                'Nama Pasien' => $item->pegawai->name,
                'Tenaga Medis' => $item->tenagaMedis->nama_tenaga_medis,
                'Penyakit' => $item->penyakit->nama_penyakit,
                'Jumlah Jenis Obat' => $item->details->count(),
            ];
        });

        return Excel::download(new ReportExport($data, ['Waktu Permintaan', 'NIK', 'Nama Pasien', 'Tenaga Medis', 'Penyakit', 'Jumlah Jenis Obat']), 'Laporan_Permintaan_Obat.xlsx');
    }

    public function kwitansi(Request $request)
    {
        $query = Kwitansi::with(['rekamMedis.pegawai'])->latest('tanggal_terbit');
        
        if ($request->search) {
            $query->whereHas('rekamMedis.pegawai', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }
        
        $query = $this->applyDateFilter($query, $request, 'tanggal_terbit');
        $data = $query->paginate(10)->appends($request->all());
        
        return view('admin.laporan.kwitansi', compact('data'));
    }

    public function exportKwitansi(Request $request)
    {
        $query = Kwitansi::with(['rekamMedis.pegawai'])->latest('tanggal_terbit');
        
        if ($request->search) {
            $query->whereHas('rekamMedis.pegawai', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }
        
        $query = $this->applyDateFilter($query, $request, 'tanggal_terbit');
        
        $data = $query->get()->map(function ($item) {
            return [
                'Tanggal Terbit' => $item->tanggal_terbit,
                'No Rekam Medis' => $item->id_rekam_medis,
                'NIK Pasien' => $item->rekamMedis->pegawai->nik ?? '-',
                'Nama Pasien' => $item->rekamMedis->pegawai->name ?? '-',
                'Total Biaya' => $item->total_biaya,
                'Status' => ucfirst(str_replace('_', ' ', $item->status)),
                'Tanggal Lunas' => $item->tanggal_lunas ?? '-',
            ];
        });

        return Excel::download(new ReportExport($data, ['Tanggal Terbit', 'No Rekam Medis', 'NIK Pasien', 'Nama Pasien', 'Total Biaya', 'Status', 'Tanggal Lunas']), 'Laporan_Kwitansi.xlsx');
    }

    public function kunjungan(Request $request)
    {
        // Hanya memunculkan presensi medis (sakit, hamil, laktasi) untuk pasien
        $presensi = DB::table('presensi')
            ->join('pegawai', 'presensi.id_pegawai', '=', 'pegawai.id')
            ->where('presensi.tipe', '!=', 'umum')
            ->select('pegawai.name as pasien', 'pegawai.nik', 'presensi.created_at as tanggal', DB::raw("CONCAT('Presensi - ', presensi.tipe) as keperluan"));

        $konsultasi = DB::table('rekam_medis')
            ->join('pegawai', 'rekam_medis.id_pegawai', '=', 'pegawai.id')
            ->select('pegawai.name as pasien', 'pegawai.nik', 'rekam_medis.tanggal_periksa as tanggal', DB::raw("'Konsultasi Dokter' as keperluan"));

        $permintaan = DB::table('permintaan_obat')
            ->join('pegawai', 'permintaan_obat.id_pegawai', '=', 'pegawai.id')
            ->select('pegawai.name as pasien', 'pegawai.nik', 'permintaan_obat.waktu_permintaan as tanggal', DB::raw("'Permintaan Obat' as keperluan"));

        $unionQuery = $presensi->unionAll($konsultasi)->unionAll($permintaan);
        
        $query = DB::query()->fromSub($unionQuery, 'kunjungan');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('pasien', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $data = $query->orderBy('tanggal', 'desc')->paginate(10)->appends($request->all());
        
        return view('admin.laporan.kunjungan', compact('data'));
    }

    public function exportKunjungan(Request $request)
    {
        $presensi = DB::table('presensi')
            ->join('pegawai', 'presensi.id_pegawai', '=', 'pegawai.id')
            ->where('presensi.tipe', '!=', 'umum')
            ->select('pegawai.name as pasien', 'pegawai.nik', 'presensi.created_at as tanggal', DB::raw("CONCAT('Presensi - ', presensi.tipe) as keperluan"));

        $konsultasi = DB::table('rekam_medis')
            ->join('pegawai', 'rekam_medis.id_pegawai', '=', 'pegawai.id')
            ->select('pegawai.name as pasien', 'pegawai.nik', 'rekam_medis.tanggal_periksa as tanggal', DB::raw("'Konsultasi Dokter' as keperluan"));

        $permintaan = DB::table('permintaan_obat')
            ->join('pegawai', 'permintaan_obat.id_pegawai', '=', 'pegawai.id')
            ->select('pegawai.name as pasien', 'pegawai.nik', 'permintaan_obat.waktu_permintaan as tanggal', DB::raw("'Permintaan Obat' as keperluan"));

        $unionQuery = $presensi->unionAll($konsultasi)->unionAll($permintaan);
        
        $query = DB::query()->fromSub($unionQuery, 'kunjungan');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('pasien', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $data = $query->orderBy('tanggal', 'desc')->get()->map(function ($item) {
            return [
                'Tanggal Kunjungan' => $item->tanggal,
                'NIK' => $item->nik,
                'Nama Pasien' => $item->pasien,
                'Keperluan' => ucwords($item->keperluan),
            ];
        });

        return Excel::download(new ReportExport($data, ['Tanggal Kunjungan', 'NIK', 'Nama Pasien', 'Keperluan']), 'Laporan_Semua_Kunjungan.xlsx');
    }
}