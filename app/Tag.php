<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

use Sqits\UserStamps\Concerns\HasUserStamps;

class Tag extends Model
{
      use Translatable;
    use SoftDeletes;
    use HasUserStamps;
    protected $guarded = [];
    public $translatedAttributes = ['name'];

    public function post()
    {
        return $this->belongsToMany('App\Post');
    }
    // public function user(){
    //     return $this->belongsTo('App\User');
    // }

    // protected $fillable = [
    // 	'tag_name'
    // ];

    // protected $dates = [
    //     'created_at',
    //     'updated_at',
    //     'deleted_at',
    // ];

    protected static function boot()
    {
        parent::boot();
    
        static::deleting(function($item) {
            $relationMethods = ['post'];
    
            foreach ($relationMethods as $relationMethod) {
                if ($item->$relationMethod()->count() > 0) {
                   
                    return false;
                 //  return redirect()->back()->with('success', 'Data Added successfully.');
                }
            }
        });
    }



}



