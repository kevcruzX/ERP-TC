<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Competencies extends Model
{
    protected $fillable = [
        'name',
        'type',
        'created_by',
    ];

    public static $types = [
        'technical' => 'Technical',
        'organizational' => 'Organizational',
        'behavioural' => 'Behavioural',
    ];
}
