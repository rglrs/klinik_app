<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPermintaanObat extends Model
{
    protected $table = 'detail_permintaan_obat';
    
    protected $guarded = [];

    public function permintaan()
    {
        return $this->belongsTo(PermintaanObat::class, 'id_permintaan', 'id_permintaan');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat', 'id_obat');
    }
}