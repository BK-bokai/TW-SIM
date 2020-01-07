<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluate_tasks extends Model
{
    protected $table = 'evaluate_tasks';
    protected $fillable = [
        'Time_Period'
    ];

    public function user()
    {
        /**
         * User::class related 关联模型
         * user_id ownerKey 当前表关联字段
         * id relation 关联表字段，这里指 user 表
         */
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
