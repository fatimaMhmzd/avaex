<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('externalPostId')->unsigned();
            $table->foreign('externalPostId')
                ->references('id')->on('external_posts')
                ->onDelete('no action');
            $table->string('partnumber');
            $table->string('shipment');
            $table->float('weight')->nullable();
            $table->float('width')->nullable();
            $table->float('height')->nullable();
            $table->float('lenght')->nullable();
            $table->float('cost')->nullable();
            $table->string('brand')->nullable()->nullable();
            $table->string('Link')->nullable()->nullable();
            $table->integer('boxnumber')->default(1);
            $table->string('typeId')->nullable();
            $table->bigInteger('sizeId')->unsigned()->nullable();
            $table->boolean('needKarton')->nullable();
            $table->bigInteger('insuranceId')->unsigned()->nullable();
            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->bigInteger('serviceId ')->unsigned()->nullable();
            $table->bigInteger('componyId')->unsigned()->nullable();
            $table->bigInteger('componyTypeId')->unsigned()->nullable();
            $table->boolean('isUsed')->nullable();
            $table->bigInteger('getterAddressId')->unsigned();
            $table->integer('price')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('external_orders');
    }
}
