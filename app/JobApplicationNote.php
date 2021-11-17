<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobApplicationNote extends Model
{
    protected $fillable = [
        'application_id',
        'note_created',
        'note',
        'created_by',
    ];

    public function noteCreated()
    {
        return $this->hasOne('App\User', 'id', 'note_created');
    }
}
