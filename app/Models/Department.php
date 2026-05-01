<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';
    protected $guarded = ['id'];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}