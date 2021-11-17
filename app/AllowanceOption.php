<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllowanceOption extends Model
{
    protected $fillable = [
        'name',
        'created_by',
    ];
}
