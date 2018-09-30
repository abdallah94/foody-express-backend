<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(['email' => 'kfc@gmail.com', 'password' => Hash::make('kfc'), 'role' => "RESTAURANT", 'restaurant_id' => 1]);
        User::create(['email' => 'pizza@gmail.com', 'password' => Hash::make('pizza'), 'role' => "RESTAURANT", 'restaurant_id' => 2]);
        User::create(['email' => 'tche@gmail.com', 'password' => Hash::make('tche'), 'role' => "RESTAURANT", 'restaurant_id' => 3]);
        User::create(['email' => 'admin@gmail.com', 'password' => Hash::make('admin'), 'role' => "ADMIN"]);
        User::create(['email' => 'delivery@gmail.com', 'password' => Hash::make('delivery'), 'role' => "DELIVERY"]);
    }
}
