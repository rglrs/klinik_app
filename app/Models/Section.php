<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table = 'sections';
    protected $guarded = ['id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}