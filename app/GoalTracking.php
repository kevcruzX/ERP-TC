<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoalTracking extends Model
{
    protected $fillable = [
        'branch',
        'goal_type',
        'start_date',
        'end_date',
        'subject',
        'target_achievement',
        'description',
        'created_by',
        'rating',
    ];

    public function goalType()
    {
        return $this->hasOne('App\GoalType', 'id', 'goal_type');
    }

    public function branches()
    {
        return $this->hasOne('App\Branch', 'id', 'branch');
    }

    public static $status = [
        'Not Started',
        'In Progress',
        'Completed',
    ];
}
