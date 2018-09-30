<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'restaurant_id'];
    public $timestamps = false;

    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant');
    }

    public function items(){
        return $this->hasMany('App\Item');
    }
}
