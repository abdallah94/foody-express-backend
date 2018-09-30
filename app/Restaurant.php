<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = ['name', 'phone'];
    public $timestamps = false;

    public function orders(){
        return $this->hasMany('App\Order');
    }
    public function user()
    {
        return $this->hasOne('App\User')->withDefault();
    }

    public function items(){
        return $this->hasMany('App\Item');
    }

    public function categories(){
        return $this->hasMany('App\Category');
    }

    public function restaurantDeliveries(){
        return $this->hasMany('App\RestaurantDelivery');
    }
}
