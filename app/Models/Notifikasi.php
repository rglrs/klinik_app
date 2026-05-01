<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Notifikasi extends Model
{
    use HasUuids;

    protected $table = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
}