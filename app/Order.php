<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


use Sqits\UserStamps\Concerns\HasUserStamps;

class Order extends Model
{
       use HasUserStamps;
  //   use SoftDeletes;

    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(Client::class);

    }//end of client
    public function store()
    {
        return $this->belongsTo(Store::class);

    }//end of store

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_order')->withPivot('quantity','price','transport');

    }//end of products

}//end of model
