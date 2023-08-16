<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDamageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('damage', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('numberParcel')->unsigned();
            $table->bigInteger('userId')->unsigned();
            $table->string('typeShipment');
            $table->string('Shipment');
            $table->bigInteger('price');
            $table->string('brandName');
            $table->string('shabaNumber');
            $table->text('description')->nullable();
            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->string('image4')->nullable();
            $table->string('cardImage')->nullable();
            $table->string('status');
            $table->softDeletes();
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
        Schema::dropIfExists('damage');
    }
}
