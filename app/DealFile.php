<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class DealFile extends Model
{
    protected $fillable = [
        'deal_id','file_name','file_path'
    ];

}
