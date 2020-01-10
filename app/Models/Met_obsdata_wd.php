<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Met_obsdata_wd extends Model
{
    protected $table = 'met_obsdata_wd';
    protected $fillable = [
        'Filename','Path','year','month','day','date'
    ];
}
