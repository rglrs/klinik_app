<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasUuids, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
    protected $hidden = ['password'];
}