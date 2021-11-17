<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DucumentUpload extends Model
{
    protected $fillable = [
        'name',
        'role',
        'document',
        'description',
        'created_by',
    ];
    
}
