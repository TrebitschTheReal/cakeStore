<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCakeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cakes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 60);
            $table->mediumText('desc')->nullable();
            $table->mediumInteger('ingredients_price_sum')->nullable();
            $table->mediumInteger('work_hours')->nullable();
            $table->mediumInteger('work_price')->nullable();
            $table->tinyInteger('tax')->nullable();
            $table->mediumInteger('selling_price')->nullable();
            $table->mediumInteger('profit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cake');
    }
}
