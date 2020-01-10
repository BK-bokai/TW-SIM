<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Met_simdata_wd extends Model
{
    protected $table = 'met_simdata_wd';
    protected $fillable = [
        'Filename','Path','year','month','day','date'
    ];
}
