<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['restaurant_rating', 'delivery_rating','foody_express_rating',];
    public $timestamps = false;

    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public function items(){
        return $this->hasMany('App\Item');
    }

    public function categories(){
        return $this->hasMany('App\Category');
    }

}
