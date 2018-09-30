<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class size extends Model
{
    protected $fillable = ['name', 'price'];
    public $timestamps = false;

//TODO:CHECK THIS
    public function sizes()
    {
        return $this->belongsToMany('App\Item')->withPivot('price');
    }
}
