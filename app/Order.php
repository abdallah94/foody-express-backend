<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
        'first_name','last_name', 'email', 'phone', 'total', 'location','restaurant_id','items','remarks'
    ];
    public function items()
    {
        return $this->belongsToMany('App\Item')->withTimestamps()->withPivot('number','extras','size','notes');
    }

    public function restaurant(){
        return $this->belongsTo('App\Restaurant');
    }

    public function rating(){
        return $this->hasOne('App\Rating');
    }
}
