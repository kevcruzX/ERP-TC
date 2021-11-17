<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    protected $fillable = [
        'comment',
        'task_id',
        'user_id',
        'user_type',
        'created_by',
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'created_by');
    }



}
