<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $fillable = [
        'project_id',
        'task_id',
        'date',
        'time',
        'description',
        'created_by',
    ];

    public function project()
    {
        return $this->hasOne('App\Project', 'id', 'project_id');
    }

    public function task()
    {
        return $this->hasOne('App\ProjectTask', 'id', 'task_id');
    }
}
