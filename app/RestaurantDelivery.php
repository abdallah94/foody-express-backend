<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RestaurantDelivery extends Model
{
    protected $table = 'restaurant_deliveries';
    protected $fillable = ['area', 'price'];
    public $timestamps = false;

    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant');
    }
}
