<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventEmployee extends Model
{
    protected $fillable = [
        'event_id',
        'employee_id',
        'created_by',
    ];
}
