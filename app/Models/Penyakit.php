<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Penyakit extends Model
{
    use HasUuids;

    protected $table = 'penyakit';
    protected $primaryKey = 'id_penyakit';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
}