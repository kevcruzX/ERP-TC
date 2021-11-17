<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    protected $fillable = [
        'employee_id',
        'allowance_option',
        'title',
        'amount',
        'created_by',
    ];

    public function employee()
    {
        return $this->hasOne('App\Employee', 'id', 'employee_id')->first();
    }

    public function allowance_option()
    {
        return $this->hasOne('App\AllowanceOption', 'id', 'allowance_option')->first();
    }

}
