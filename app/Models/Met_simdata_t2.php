<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Met_simdata_t2 extends Model
{
    protected $table = 'met_simdata_t2';
    protected $fillable = [
        'Filename','Path','year','month','day','date'
    ];
}
