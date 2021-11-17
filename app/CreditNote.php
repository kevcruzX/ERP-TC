<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditNote extends Model
{
    protected $fillable = [
        'invoice',
        'customer',
        'amount',
        'date',
    ];

    public function customer()
    {
        return $this->hasOne('App\Customer', 'customer_id', 'customer');
    }
}
