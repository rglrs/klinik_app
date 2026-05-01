<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Obat extends Model
{
    use HasUuids;

    protected $table = 'obat';
    protected $primaryKey = 'id_obat';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
}