<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenaga_medis', function (Blueprint $table) {
            $table->uuid('id_tenaga_medis')->primary();
            $table->uuid('id_user')->unique();
            $table->string('kode_tenaga_medis');
            $table->string('nik')->unique();
            $table->string('nama_tenaga_medis');
            $table->string('jabatan');
            $table->timestamps();
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('name');
            $table->string('gender');
            $table->string('phone')->nullable();
            $table->foreignId('position_id')->nullable()->constrained('positions')->nullOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('penyakit', function (Blueprint $table) {
            $table->uuid('id_penyakit')->primary();
            $table->string('nama_penyakit');
            $table->timestamps();
        });

        Schema::create('obat', function (Blueprint $table) {
            $table->uuid('id_obat')->primary();
            $table->string('nama_obat');
            $table->string('nama_batch');
            $table->integer('stok_saat_ini');
            $table->string('satuan');
            $table->date('expired_date');
            $table->integer('reorder_level');
            $table->enum('jenis_obat', ['tablet', 'kapsul', 'sirup', 'salep', 'injeksi', 'tetes', 'puyer']);
            $table->timestamps();
        });

        Schema::create('permintaan_obat', function (Blueprint $table) {
            $table->uuid('id_permintaan')->primary();
            $table->foreignId('id_pegawai')->constrained('pegawai', 'id')->cascadeOnDelete();
            $table->uuid('id_tenaga_medis');
            $table->uuid('id_penyakit');
            $table->timestamp('waktu_permintaan')->useCurrent();
            $table->timestamps();
            $table->foreign('id_tenaga_medis')->references('id_tenaga_medis')->on('tenaga_medis')->onDelete('cascade');
            $table->foreign('id_penyakit')->references('id_penyakit')->on('penyakit')->onDelete('cascade');
        });

        Schema::create('detail_permintaan_obat', function (Blueprint $table) {
            $table->id();
            $table->uuid('id_permintaan');
            $table->uuid('id_obat');
            $table->integer('jumlah_diminta');
            $table->timestamps();
            $table->foreign('id_permintaan')->references('id_permintaan')->on('permintaan_obat')->onDelete('cascade');
            $table->foreign('id_obat')->references('id_obat')->on('obat')->onDelete('cascade');
        });

        Schema::create('presensi', function (Blueprint $table) {
            $table->uuid('id_presensi')->primary();
            $table->foreignId('id_pegawai')->constrained('pegawai', 'id')->cascadeOnDelete();
            $table->timestamp('jam_masuk')->useCurrent();
            $table->timestamp('jam_keluar')->nullable();
            $table->enum('tipe', ['sakit', 'hamil', 'laktasi', 'umum']);
            $table->timestamps();
        });

        Schema::create('presensi_tenaga_medis', function (Blueprint $table) {
            $table->uuid('id_presensi')->primary();
            $table->uuid('id_tenaga_medis');
            $table->timestamp('jam_masuk')->useCurrent();
            $table->timestamp('jam_keluar')->nullable();
            $table->enum('keterangan', ['hadir', 'izin']);
            $table->timestamps();
            $table->foreign('id_tenaga_medis')->references('id_tenaga_medis')->on('tenaga_medis')->onDelete('cascade');
        });

        Schema::create('notifikasi', function (Blueprint $table) {
            $table->uuid('id_notifikasi')->primary();
            $table->uuid('id_obat');
            $table->string('pesan');
            $table->string('status');
            $table->timestamps();
            $table->foreign('id_obat')->references('id_obat')->on('obat')->onDelete('cascade');
        });

        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->uuid('id_rekam_medis')->primary();
            $table->foreignId('id_pegawai')->constrained('pegawai', 'id')->cascadeOnDelete();
            $table->uuid('id_tenaga_medis');
            $table->text('keluhan');
            $table->string('tensi')->nullable();
            $table->float('suhu')->nullable();
            $table->text('diagnosa');
            $table->text('tindakan')->nullable();
            $table->enum('status_perawatan', ['rawat_jalan', 'rawat_inap'])->default('rawat_jalan');
            $table->timestamp('tanggal_periksa')->useCurrent();
            $table->timestamps();
            $table->foreign('id_tenaga_medis')->references('id_tenaga_medis')->on('tenaga_medis')->onDelete('cascade');
        });

        Schema::create('kwitansi', function (Blueprint $table) {
            $table->uuid('id_kwitansi')->primary();
            $table->uuid('id_rekam_medis')->unique();
            $table->float('total_biaya')->nullable();
            $table->enum('status', ['belum_lunas', 'lunas'])->default('belum_lunas');
            $table->timestamp('tanggal_terbit')->useCurrent();
            $table->timestamp('tanggal_lunas')->nullable();
            $table->timestamps();
            $table->foreign('id_rekam_medis')->references('id_rekam_medis')->on('rekam_medis')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kwitansi');
        Schema::dropIfExists('rekam_medis');
        Schema::dropIfExists('notifikasi');
        Schema::dropIfExists('presensi_tenaga_medis');
        Schema::dropIfExists('presensi');
        Schema::dropIfExists('detail_permintaan_obat');
        Schema::dropIfExists('permintaan_obat');
        Schema::dropIfExists('obat');
        Schema::dropIfExists('penyakit');
        Schema::dropIfExists('pegawai');
        Schema::dropIfExists('positions');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('tenaga_medis');
    }
};