<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddRestaurant extends Model
{
    protected $fillable = ['first_name', 'last_name','restaurant','phone','email','address'];
}
