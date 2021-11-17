<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = [
        'name', 'created_by', 'description',
    ];


    protected $hidden = [

    ];
}
