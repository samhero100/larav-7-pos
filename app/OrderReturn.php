<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;

class OrderReturn extends Model
{
      use HasUserStamps;
  //  use SoftDeletes;

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
      return $this->belongsToMany(Product::class, 'product_order_return')->withPivot('quantity','price','transport');

  }//end of products

}//end of model
