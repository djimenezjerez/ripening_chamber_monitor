<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagnitudeRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magnitude_room', function (Blueprint $table) {
            $table->bigInteger('room_id')->unsigned();
            $table->foreign('room_id')->references('id')->on('rooms')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('magnitude_id')->unsigned();
            $table->foreign('magnitude_id')->references('id')->on('magnitudes')->onUpdate('cascade')->onDelete('cascade');
            $table->float('min_limit', 5, 2);
            $table->float('max_limit', 5, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('magnitude_room');
    }
}
