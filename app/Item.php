<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //
    protected $fillable = ['name', 'description', 'price', 'restaurant_id', 'category_id'];
    public $timestamps = false;

    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

//TODO:CHECK THIS
    public function items()
    {
        return $this->belongsToMany('App\Order')->withTimestamps()->withPivot('number','extras','size','notes');
    }

    public function sizes()
    {
        return $this->belongsToMany('App\Size')->withPivot('price');
    }

    public function extras()
    {
        return $this->hasMany('App\Extra');
    }
}
