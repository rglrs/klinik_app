<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RekamMedis extends Model
{
    use HasUuids;
    protected $table = 'rekam_medis';
    protected $primaryKey = 'id_rekam_medis';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function pegawai() { return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id'); }
    public function tenagaMedis() { return $this->belongsTo(TenagaMedis::class, 'id_tenaga_medis', 'id_tenaga_medis'); }
}