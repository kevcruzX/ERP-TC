<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductService extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'sale_price',
        'purchase_price',
        'tax_id',
        'category_id',
        'unit_id',
        'type',
        'created_by',
    ];

    public function taxes()
    {
        return $this->hasOne('App\Tax', 'id', 'tax_id')->first();
    }

    public function unit()
    {
        return $this->hasOne('App\ProductServiceUnit', 'id', 'unit_id')->first();
    }

    public function category()
    {
        return $this->hasOne('App\ProductServiceCategory', 'id', 'category_id');
    }

    public function tax($taxes)
    {
        $taxArr = explode(',', $taxes);

        $taxes  = [];
        foreach($taxArr as $tax)
        {
            $taxes[] = Tax::find($tax);
        }

        return $taxes;
    }

    public function taxRate($taxes)
    {
        $taxArr  = explode(',', $taxes);
        $taxRate = 0;
        foreach($taxArr as $tax)
        {
            $tax     = Tax::find($tax);
            $taxRate += $tax->rate;
        }

        return $taxRate;
    }




}
