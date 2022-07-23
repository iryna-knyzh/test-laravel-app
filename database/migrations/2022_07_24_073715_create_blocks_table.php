<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('storage_id')->index();
            $table->unsignedSmallInteger('type');

            $table->timestamps();
        });

        Schema::create('block_booking', function (Blueprint $table) {
            $table->unsignedInteger('block_id')->index();
            $table->unsignedInteger('booking_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blocks');
    }
}
