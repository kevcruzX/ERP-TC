<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormFieldResponse extends Model
{
    protected $fillable = [
        'form_id',
        'subject_id',
        'name_id',
        'email_id',
        'user_id',
        'pipeline_id',
    ];
}
