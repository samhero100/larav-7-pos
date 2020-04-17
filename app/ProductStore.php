<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProductStore extends Model
{
    use SoftDeletes;

       protected $guarded = [];
    public $timestamps = false;

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_store')->withPivot('first_stock','stock');

    }//end of products

}//end of model
