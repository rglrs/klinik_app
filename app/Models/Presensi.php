<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Presensi extends Model
{
    use HasUuids;

    protected $table = 'presensi';
    protected $primaryKey = 'id_presensi';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id');
    }
}