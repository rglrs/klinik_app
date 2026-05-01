<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PresensiTenagaMedis extends Model
{
    use HasUuids;

    protected $table = 'presensi_tenaga_medis';
    protected $primaryKey = 'id_presensi';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function tenagaMedis()
    {
        return $this->belongsTo(TenagaMedis::class, 'id_tenaga_medis', 'id_tenaga_medis');
    }
}