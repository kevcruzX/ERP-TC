<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bug extends Model
{
    protected $fillable = [
        'bug_id',
        'project_id',
        'title',
        'priority',
        'start_date',
        'due_date',
        'description',
        'status',
        'assign_to',
        'created_by',
    ];

    public static $priority = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
    ];

    public function bug_status()
    {
        return $this->hasOne('App\BugStatus', 'id', 'status');
    }

    public function assignTo()
    {
        return $this->hasOne('App\User', 'id', 'assign_to');
    }

    public function createdBy()
    {
        return $this->hasOne('App\User', 'id', 'created_by');
    }

    public function comments()
    {
        return $this->hasMany('App\BugComment', 'bug_id', 'id')->orderBy('id', 'DESC');
    }

    public function bugFiles()
    {
        return $this->hasMany('App\BugFile', 'bug_id', 'id')->orderBy('id', 'DESC');
    }
    public function project()
    {
        return $this->hasOne('App\Project', 'id', 'project_id');
    }
    public function users()
    {
        return User::whereIn('id', explode(',', $this->assign_to))->get();
    }
}
