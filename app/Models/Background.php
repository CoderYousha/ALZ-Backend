<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Background extends Model
{
    protected $table = 'background';
    protected $fillable = [
        'image'
    ];
}
