<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlockTypeParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('block_type_params', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('type');
            $table->unsignedFloat('length');
            $table->unsignedFloat('weight');
            $table->unsignedFloat('height');

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
        Schema::dropIfExists('block_type_params');
    }
}
