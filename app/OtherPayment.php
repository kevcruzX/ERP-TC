<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtherPayment extends Model
{
    protected $fillable = [
        'employee_id',
        'title',
        'amount',
        'created_by',
    ];

    public function employee()
    {
        return $this->hasOne('App\Employee', 'id', 'employee_id')->first();
    }
}
