<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IpRestrict extends Model
{
    protected $fillable = [
        'ip',
        'created_by',
    ];
}
