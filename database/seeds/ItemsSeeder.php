<?php

use Illuminate\Database\Seeder;
use App\Item;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Item::create(['name' => 'Family Meal', 'description' => 'meal for the family!', 'price' => 100, 'restaurant_id' => 1]);
        Item::create(['name' => 'Two Mini Burgers', 'description' => 'small meal', 'price' => 10, 'restaurant_id' => 1]);
        Item::create(['name' => 'Chicken Sandwich', 'description' => 'A delicious fired chicken sanwich', 'price' => 20, 'restaurant_id' => 1]);
        Item::create(['name' => 'Lunch meal', 'description' => '2 fired chicken pieces with fries and drink', 'price' => 25, 'restaurant_id' => 1]);
        Item::create(['name' => 'Small cheese Pizza', 'description' => 'Small cheese pizza- comes with fries and drink', 'price' => 30, 'restaurant_id' => 2]);
        Item::create(['name' => 'Medium Chicken  Pizza', 'description' => 'Medium Pizza with chicken, cheese and vegetables toppings', 'price' => 50, 'restaurant_id' => 2]);
        Item::create(['name' => 'Large Chicken  Pizza', 'description' => 'Large Pizza with chicken, cheese and vegetables toppings', 'price' => 100, 'restaurant_id' => 2]);
        Item::create(['name' => 'Large Vegetables  Pizza', 'description' => 'Large Pizza with cheese and vegetables toppings', 'price' => 90, 'restaurant_id' => 2]);
        Item::create(['name' => 'Cheese Burger', 'description' => 'Cheese burger with pickels and fries', 'price' => 45, 'restaurant_id' => 3]);
        Item::create(['name' => 'Hot Wings', 'description' => '12 How wings with sides and drink', 'price' => 50, 'restaurant_id' => 3]);
        Item::create(['name' => 'Salmon', 'description' => 'Salmon meal with rice', 'price' => 85, 'restaurant_id' => 3]);






    }
}
