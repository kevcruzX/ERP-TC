<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'status',
        'description',
    ];

    public function tasks()
    {
        return $this->hasMany('App\ProjectTask', 'milestone_id', 'id');
    }
}
