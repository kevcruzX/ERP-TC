<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    protected $fillable = [
        'form_id',
        'name',
        'type',
        'created_by',
    ];
}
