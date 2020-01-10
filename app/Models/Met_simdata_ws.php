<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Met_simdata_ws extends Model
{
    protected $table = 'met_simdata_ws';
    protected $fillable = [
        'Filename','Path','year','month','day','date'
    ];
}
