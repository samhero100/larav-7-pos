<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientTranslation extends Model
{

    public $timestamps = false;
    protected $fillable = ['name', 'address'];

}
