<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Met_obsdata_ws extends Model
{
    protected $table = 'met_obsdata_ws';
    protected $fillable = [
        'Filename','Path','year','month','day','date'
    ];
}
