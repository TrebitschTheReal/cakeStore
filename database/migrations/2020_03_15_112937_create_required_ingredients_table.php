<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequiredIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('required_ingredients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ingredient_id');
            $table->mediumInteger('ingredient_quantity');
            $table->bigInteger('cake_id');
            $table->mediumInteger('ingredient_price');

            $table->foreign('ingredient_id')
                ->references('id')
                ->on('ingredient')
                ->onDelete('cascade');

            $table->foreign('cake_id')
                ->references('id')
                ->on('cake')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('required_ingredients');
    }
}