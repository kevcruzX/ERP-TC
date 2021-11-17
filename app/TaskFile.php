<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskFile extends Model
{
    protected $fillable = [
        'file',
        'name',
        'extension',
        'file_size',
        'task_id',
        'user_type',
        'created_by',
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'created_by');
    }
}
