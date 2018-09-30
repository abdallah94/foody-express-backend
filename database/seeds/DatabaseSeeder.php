<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RestaurantsSeeder::class);
        $this->call(ItemsSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(sizesSeeder::class);
        Model::reguard();
    }
}
