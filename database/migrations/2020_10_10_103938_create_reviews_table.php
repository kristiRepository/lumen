<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trip_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->text('body');
            $table->integer('rating');
            $table->timestamps(); 
            
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
