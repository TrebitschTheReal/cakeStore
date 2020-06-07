<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredients', function (Blueprint $table) {
           $table->increments('id');
            $table->string('name', 60);
            $table->mediumText('desc')->nullable();
           $table->mediumInteger('unit_category');
           $table->string('uploaded_unit_type')->nullable();
           $table->mediumInteger('uploaded_unit_price')->nullable();
           $table->mediumInteger('uploaded_unit_quantity')->nullable();
            $table->double('unit_price',20,4);
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
        Schema::dropIfExists('ingredient');
    }
}
