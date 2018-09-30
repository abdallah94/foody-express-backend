<?php

use Illuminate\Database\Seeder;
use App\Size;
class sizesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Size::create(['name' => 'small']);
        Size::create(['name' => 'medium']);
        Size::create(['name' => 'large']);
    }
}
