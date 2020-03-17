<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

use Sqits\UserStamps\Concerns\HasUserStamps;

class ProductStore extends Model
{
      use Translatable;
      use HasUserStamps;
      use SoftDeletes;

    
    protected $guarded = ['id'];

    public $translatedAttributes = ['name'];



    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_store');

    }//end of orders


}//end of model
