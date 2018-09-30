<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('total');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('location');
            $table->string('phone');
            $table->string('remarks')->nullable();
            $table->string('email')->nullable();
            $table->boolean('accepted')->default(false);
            $table->boolean('delivery_accepted')->default(false);
            $table->boolean('delivery')->default(true);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('delivery_time')->nullable();
            $table->integer('delivery_fee')->default(0);
            $table->unsignedInteger('restaurant_id')->nullable();
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
