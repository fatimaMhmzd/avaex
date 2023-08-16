<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_options', function (Blueprint $table) {
            $table->id();
            $table->string('property');
            $table->bigInteger('vehicleId')->unsigned();
            $table->foreign('vehicleId')
                ->references('id')->on('heavy_vehicles')
                ->onDelete('no action');

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
        Schema::dropIfExists('vehicle_options');
    }
}
