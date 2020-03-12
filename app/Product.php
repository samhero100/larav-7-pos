<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

use Sqits\UserStamps\Concerns\HasUserStamps;

class Product extends Model
{
      use Translatable;
      use HasUserStamps;
      use SoftDeletes;

    
    protected $guarded = ['id'];

    public $translatedAttributes = ['name', 'description'];
    protected $appends = ['image_path', 'profit_percent'];


    public function getImagePathAttribute()
    {
        return asset('uploads/product_images/' . $this->image);

    }//end of image path attribute

    public function getProfitPercentAttribute()
    {
        $profit = $this->sale_price - $this->purchase_price;
        $profit_percent = $profit * 100 / $this->purchase_price;
        return number_format($profit_percent, 2);

    }//end of get profit attribute

    public function category()
    {
        return $this->belongsTo(Category::class);

    }//end fo category
    // public function store()
    // {
    //     return $this->belongsTo(Store::class);

    // }//end fo category
    public function stores()
    {
        return $this->belongsToMany(Store::class, 'product_store')->withPivot('first_stock','stock');

    }//end of orders
    public function total_stock()
    {
        return $this->stores()->sum('stock');
    }
    public function total_first_stock()
    {
        return $this->stores()->sum('first_stock');
    }

    public function total_sales()
    {
        return $this->orders()->sum('quantity');
    }
    public function total_sales_return()
    {
        return $this->orders_return()->sum('quantity');
    }

    public function total_purch()
    {
        return $this->orders_suppliers()->sum('quantity');
    }
    // public function stock()
    // {
    //     return $this->stores()->sum('stock');
    // }

    public function total_purch_return()
    {
        return $this->orders_suppliers_return()->sum('quantity');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'product_order');

    }//end of orders
    public function orders_return()
    {
        return $this->belongsToMany(OrderReturn::class, 'product_order_return');

    }//end of orders

    public function orders_suppliers()
    {
        return $this->belongsToMany(OrderSupplier::class, 'product_order_supplier');

    }//end of orders
    public function orders_suppliers_return()
    {
        return $this->belongsToMany(OrderSupplierReturn::class, 'product_order_supplier_return');

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
