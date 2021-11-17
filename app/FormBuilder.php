<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormBuilder extends Model
{
    protected $fillable = [
        'form_id',
        'name',
        'type',
        'created_by',
    ];

    public static $fieldTypes = [
        'text' => 'Text',
        'email' => 'Email',
        'number' => 'Number',
        'date' => 'Date',
        'textarea' => 'Textarea',
    ];

    public function form_field()
    {
        return $this->hasMany('App\FormField', 'form_id', 'id');
    }

    public function fieldResponse()
    {
        return $this->hasOne('App\FormFieldResponse', 'form_id', 'id');
    }

    public function response()
    {
        return $this->hasMany('App\FormResponse', 'form_id', 'id');
    }
}
