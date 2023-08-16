<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeavyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('heavy_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('heavyPostId')->unsigned();
            $table->foreign('heavyPostId')
                ->references('id')->on('heavy_posts')
                ->onDelete('no action');
            $table->string('shipment');
            $table->float('Weight');
            $table->float('width')->nullable();
            $table->float('height')->nullable();
            $table->float('lenght')->nullable();
            $table->integer('Value');
            $table->integer('count');
            $table->bigInteger('vehicle')->unsigned();
            $table->foreign('vehicle')
                ->references('id')->on('vehicle_options')
                ->onDelete('no action');
            $table->bigInteger('packagingId')->unsigned()->nullable();
            $table->foreign('packagingId')
                ->references('id')->on('packagings')
                ->onDelete('no action');
            $table->dateTime('dispatch_date');
            $table->bigInteger('getterAddressId')->unsigned();
            $table->foreign('getterAddressId')
                ->references('id')->on('addresses')
                ->onDelete('no action');
            $table->bigInteger('serviceId')->unsigned();
            $table->foreign('serviceId')
                ->references('id')->on('compony_services')
                ->onDelete('no action');
            $table->integer('price')->nullable();
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
        Schema::dropIfExists('heavy_orders');
    }
}
