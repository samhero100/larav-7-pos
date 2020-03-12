<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'address'];

}
