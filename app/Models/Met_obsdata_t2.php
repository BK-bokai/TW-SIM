<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Met_obsdata_t2 extends Model
{
    protected $table = 'met_obsdata_t2';
    protected $fillable = [
        'Filename','Path','year','month','day','date'
    ];
}
