<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'date',
        'amount',
        'account_id',
        'vender_id',
        'description',
        'category_id',
        'payment_method',
        'reference',
        'created_by',
    ];

    public function category()
    {
        return $this->hasOne('App\ProductServiceCategory', 'id', 'category_id');
    }

    public function vender()
    {
        return $this->hasOne('App\Vender', 'id', 'vender_id');
    }


    public function bankAccount()
    {
        return $this->hasOne('App\BankAccount', 'id', 'account_id');
    }

}
