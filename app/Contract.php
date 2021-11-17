<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'name',
        'client_name',
        'subject',
        'value',
        'type',
        'start_date',
        'end_date',
        'notes',
        'created_by',
        'status',
    ];

    public function contract_type()
    {
        return $this->hasOne('App\ContractType', 'id', 'type');
    }

    public function client()
    {
        return $this->hasOne('App\User', 'id', 'client_name');
    }

    public static function getContractSummary($contracts)
    {
        $total = 0;

        foreach($contracts as $contract)
        {
            $total += $contract->value;
        }

        return \Auth::user()->priceFormat($total);
    }
}
