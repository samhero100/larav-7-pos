<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Sqits\UserStamps\Concerns\HasUserStamps;

class Post extends Model
{
      use Translatable;
       use HasUserStamps;
       use SoftDeletes;

       protected $guarded = ['id'];

       public $translatedAttributes = ['title', 'content'];
       protected $appends = ['image_path'];
   
   
       public function getImagePathAttribute()
       {
           return asset('uploads/post_images/' . $this->image);
   
       }//end of image path attribute
   
       public function category()
       {
           return $this->belongsTo(Category::class);
   
       }//end fo category
   
   
    public function tags()
    {
        return $this->hasMany(Tag::class);

    }//end of products

    protected static function boot()
    {
        parent::boot();
    
        static::deleting(function($item) {
            $relationMethods = ['tags'];
    
            foreach ($relationMethods as $relationMethod) {
                if ($item->$relationMethod()->count() > 0) {
                   
                    return false;
                 //  return redirect()->back()->with('success', 'Data Added successfully.');
                }
            }
        });
    }

}
