<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TenagaMedis extends Model
{
    use HasUuids;

    protected $table = 'tenaga_medis';
    protected $primaryKey = 'id_tenaga_medis';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}