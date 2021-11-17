<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InterviewSchedule extends Model
{
    protected $fillable = [
        'candidate',
        'employee',
        'date',
        'time',
        'comment',
        'employee_response',
        'created_by',
    ];

    public function applications()
    {
       return $this->hasOne('App\JobApplication','id','candidate');
    }

    public function users()
    {
        return $this->hasOne('App\User', 'id', 'employee');
    }
}
