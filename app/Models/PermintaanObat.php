<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PermintaanObat extends Model
{
    use HasUuids;
    protected $table = 'permintaan_obat';
    protected $primaryKey = 'id_permintaan';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function pegawai() { return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id'); }
    public function tenagaMedis() { return $this->belongsTo(TenagaMedis::class, 'id_tenaga_medis', 'id_tenaga_medis'); }
    public function penyakit() { return $this->belongsTo(Penyakit::class, 'id_penyakit', 'id_penyakit'); }
    public function details() { return $this->hasMany(DetailPermintaanObat::class, 'id_permintaan', 'id_permintaan'); }
}