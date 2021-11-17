<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BugComment extends Model
{
    protected $fillable = [
        'comment', 'bug_id','created_by', 'user_type',
    ];


    protected $hidden = [

    ];

    public function comment_user(){
       return User::where('id','=',$this->created_by)->first();
    }
    public function user(){
        return $this->hasOne('App\User','id','created_by');
    }

}
