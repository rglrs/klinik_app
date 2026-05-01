<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Department;
use App\Models\Section;
use App\Models\Position;
use App\Models\Pegawai;
use App\Models\TenagaMedis;
use App\Models\Penyakit;
use App\Models\Obat;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin HR',
            'email' => 'admin@ptjai.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $dokter = User::create([
            'name' => 'Dr. Andi Pratama',
            'email' => 'dokter@ptjai.com',
            'password' => Hash::make('password123'),
            'role' => 'dokter',
        ]);

        $perawat = User::create([
            'name' => 'Suster Siti Aminah',
            'email' => 'perawat@ptjai.com',
            'password' => Hash::make('password123'),
            'role' => 'perawat',
        ]);

        $deptProduksi = Department::create(['name' => 'Produksi']);
        $deptHRD = Department::create(['name' => 'HRD & General Affair']);
        $deptQA = Department::create(['name' => 'Quality Assurance']);

        $secAssembling = Section::create(['department_id' => $deptProduksi->id, 'name' => 'Assembling']);
        $secCutting = Section::create(['department_id' => $deptQA->id, 'name' => 'Cutting']);
        $secPPIC = Section::create(['department_id' => $deptHRD->id, 'name' => 'PPIC']);

        $posOperator = Position::create(['name' => 'Operator']);
        $posStaff = Position::create(['name' => 'Staff']);
        $posSPV = Position::create(['name' => 'Supervisor']);

        Pegawai::create([
            'nik' => 'EMP2026001',
            'name' => 'Budi Santoso',
            'gender' => 'Laki-laki',
            'phone' => '081234567890',
            'department_id' => $deptProduksi->id,
            'section_id' => $secAssembling->id,
            'position_id' => $posOperator->id,
        ]);

        Pegawai::create([
            'nik' => 'EMP2026002',
            'name' => 'Ayu Lestari',
            'gender' => 'Perempuan',
            'phone' => '081234567891',
            'department_id' => $deptHRD->id,
            'section_id' => $secPPIC->id,
            'position_id' => $posStaff->id,
        ]);

        Pegawai::create([
            'nik' => 'EMP2026003',
            'name' => 'Dewi Saputri',
            'gender' => 'Perempuan',
            'phone' => '081234567892',
            'department_id' => $deptQA->id,
            'section_id' => $secCutting->id,
            'position_id' => $posSPV->id,
        ]);

        TenagaMedis::create([
            'id_user' => $dokter->id_user,
            'kode_tenaga_medis' => 'DR-001',
            'nik' => 'MED2026001',
            'nama_tenaga_medis' => 'Dr. Andi Pratama',
            'jabatan' => 'Dokter Umum',
        ]);

        TenagaMedis::create([
            'id_user' => $perawat->id_user,
            'kode_tenaga_medis' => 'PR-001',
            'nik' => 'MED2026002',
            'nama_tenaga_medis' => 'Suster Siti Aminah',
            'jabatan' => 'Perawat Kepala',
        ]);

        $penyakitList = ['Influenza', 'Dyspepsia (Maag)', 'ISPA', 'Diare', 'Sakit Kepala / Migrain', 'Kelelahan (Fatigue)'];
        foreach ($penyakitList as $p) {
            Penyakit::create(['nama_penyakit' => $p]);
        }

        Obat::create([
            'nama_obat' => 'Paracetamol 500mg',
            'nama_batch' => 'BCH-2026A',
            'stok_saat_ini' => 150,
            'satuan' => 'Strip',
            'expired_date' => Carbon::now()->addYear()->format('Y-m-d'),
            'reorder_level' => 30,
            'jenis_obat' => 'tablet',
        ]);

        Obat::create([
            'nama_obat' => 'Amoxicillin 500mg',
            'nama_batch' => 'BCH-2026B',
            'stok_saat_ini' => 15,
            'satuan' => 'Strip',
            'expired_date' => Carbon::now()->addMonths(6)->format('Y-m-d'),
            'reorder_level' => 20,
            'jenis_obat' => 'kapsul',
        ]);

        Obat::create([
            'nama_obat' => 'Antasida Doen',
            'nama_batch' => 'BCH-2026C',
            'stok_saat_ini' => 80,
            'satuan' => 'Botol',
            'expired_date' => Carbon::now()->addDays(15)->format('Y-m-d'),
            'reorder_level' => 25,
            'jenis_obat' => 'sirup',
        ]);
    }
}