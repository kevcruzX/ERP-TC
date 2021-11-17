<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoalType extends Model
{
    protected $fillable = [
        'name',
        'created_by',
    ];
}
