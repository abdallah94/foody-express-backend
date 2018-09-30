<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class extra extends Model
{
    protected $fillable = ['name', 'price', 'item_id'];
    public $timestamps = false;

    public function restaurant()
    {
        return $this->belongsTo('App\Item');
    }

}
