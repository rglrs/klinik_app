<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Kwitansi extends Model
{
    use HasUuids;
    protected $table = 'kwitansi';
    protected $primaryKey = 'id_kwitansi';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'id_rekam_medis', 'id_rekam_medis');
    }
}