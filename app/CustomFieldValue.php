<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomFieldValue extends Model
{
    protected $fillable = [
        'record_id',
        'field_id',
        'value',
    ];
}
