<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobOnBoard extends Model
{
    protected $fillable = [
        'application',
        'joining_date',
        'status',
        'convert_to_employee',
        'created_by',
    ];

    public function applications()
    {
        return $this->hasOne('App\JobApplication', 'id', 'application');
    }

    public static $status = [
        '' => 'Select Status',
        'pending' => 'Pending',
        'cancel' => 'Cancel',
        'confirm' => 'Confirm',
    ];
}
