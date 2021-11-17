<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable= [
        'title',
        'agent_or_manager',
        'date',
        'time',
        'description',
        'module_type',
        'module_id',
        'created_by'

    ];
    public function task_user(){
        return $this->hasOne('App\User','id','assign_to');
    }
    public function project()
    {
        return $this->hasOne('App\Project', 'id', 'project_id');
    }
    public function comments(){
        return $this->hasMany('App\TaskComment','task_id','id')->orderBy('id','DESC');
    }

    public function taskFiles(){
        return $this->hasMany('App\TaskFile','task_id','id')->orderBy('id','DESC');
    }

    public function taskCheckList(){
        return $this->hasMany('App\CheckList','task_id','id')->orderBy('id','DESC');
    }

    public function taskCompleteCheckListCount(){
        return $this->hasMany('App\CheckList','task_id','id')->where('status','=','1')->count();
    }

    public function taskTotalCheckListCount(){
        return $this->hasMany('App\CheckList','task_id','id')->count();
    }
    public function milestone(){
        return $this->hasOne('App\Milestone','id','milestone_id');
    }
}
