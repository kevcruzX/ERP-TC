<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $fillable = [
        'branch',
        'firstname',
        'lastname',
        'contact',
        'email',
        'address',
        'expertise',
        'created_by',
    ];

    public function branches()
    {
        return $this->hasOne('App\Branch', 'id', 'branch');
    }
}
