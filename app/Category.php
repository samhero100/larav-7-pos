<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

use Sqits\UserStamps\Concerns\HasUserStamps;

class Category extends Model
{
      use Translatable;
       use HasUserStamps;
    use SoftDeletes;

       protected $guarded = [];
    public $translatedAttributes = ['name'];

    public function products()
    {
        return $this->hasMany(Product::class);

    }//end of products
    protected static function boot()
    {
        parent::boot();
    
        static::deleting(function($item) {
            $relationMethods = ['products'];
    
            foreach ($relationMethods as $relationMethod) {
                if ($item->$relationMethod()->count() > 0) {
                   
                    return false;
                 //  return redirect()->back()->with('success', 'Data Added successfully.');
                }
            }
        });
    }

}//end of model
