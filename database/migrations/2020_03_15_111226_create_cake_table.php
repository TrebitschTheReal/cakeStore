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
            $table->mediumText('desc');
            $table->mediumInteger('ingredients_price_sum');
            $table->mediumInteger('work_hours');
            $table->mediumInteger('work_price');
            $table->tinyInteger('tax');
            $table->mediumInteger('selling_price');
            $table->mediumInteger('profit');
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
