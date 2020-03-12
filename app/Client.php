<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

use Sqits\UserStamps\Concerns\HasUserStamps;

class Client extends Model
{
    use SoftDeletes;

      use Translatable;
       use HasUserStamps;

    protected $guarded = [];
    public $translatedAttributes = ['name','address'];

    // protected $casts = [
    //     'phone' => 'array'
    // ];
    protected $appends = ['image_path'];
   
   
    public function getImagePathAttribute()
    {
        return asset('uploads/clients_images/' . $this->image);

    }//end of image path attribute


    // public function getNameAttribute($value)
    // {
    //     return ucfirst($value);

    // }//end of get name attribute

    public function orders()
    {
        return $this->hasMany(Order::class);

    }//end of orders
    public function orders_return()
    {
        return $this->hasMany(OrderReturn::class);

    }//end of orders

    protected static function boot()
    {
        parent::boot();
    
        static::deleting(function($item) {
            $relationMethods = ['orders'];
    
            foreach ($relationMethods as $relationMethod) {
                if ($item->$relationMethod()->count() > 0) {
                   
                    return false;
                 //  return redirect()->back()->with('success', 'Data Added successfully.');
                }
            }
        });
    }

}//end of model
