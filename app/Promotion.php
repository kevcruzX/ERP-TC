<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'employee_id',
        'designation_id',
        'promotion_title',
        'promotion_date',
        'description',
        'created_by',
    ];

    public function designation()
    {
        return $this->hasMany('App\Designation', 'id', 'designation_id')->first();
    }

    public function employee()
    {
        return $this->hasOne('App\Employee', 'id', 'employee_id')->first();
    }
}
