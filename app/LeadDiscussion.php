<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadDiscussion extends Model
{
    protected $fillable = [
        'lead_id',
        'comment',
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'created_by');
    }
}
