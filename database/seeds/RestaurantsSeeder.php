<?php

use Illuminate\Database\Seeder;
use App\Restaurant;
class RestaurantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Restaurant::create(['name'=>'KFC','phone'=>'024356234']);
        Restaurant::create(['name'=>'Mr.Pizza','phone'=>'096874213']);
        Restaurant::create(['name'=>'Tche Tche','phone'=>'065487954']);
    }
}
