<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalPostOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_post_order', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('internalPostId')->unsigned();
            $table->foreign('internalPostId')
                ->references('id')->on('internal_posts')
                ->onDelete('no action');
            $table->bigInteger('serviceNumberParcel')->nullable();
            $table->string('partnumber');
            $table->bigInteger('servicePartnumber')->nullable();
            $table->string('shipment');
            $table->float('weight');
            $table->float('width')->nullable();
            $table->float('height')->nullable();
            $table->float('lenght')->nullable();
            $table->bigInteger('cost')->default(0);
            $table->bigInteger('value')->default(0);
            $table->integer('boxnumber')->default(1);
            $table->string('typeId');
            $table->bigInteger('sizeId')->unsigned()->nullable();
            $table->boolean('needKarton');
            $table->bigInteger('insuranceId')->unsigned();
            $table->bigInteger('serviceId ')->unsigned()->nullable();
            $table->bigInteger('componyId')->unsigned()->nullable();
            $table->bigInteger('componyTypeId')->unsigned()->nullable();
            $table->bigInteger('getterAddressId')->unsigned()->nullable();
            $table->bigInteger('price')->default(0);
            $table->string('status')->nullable();
            $table->bigInteger('print')->default(0);
            $table->bigInteger('amountCOD')->default(0);
            $table->bigInteger('packaging')->default(0);
            $table->bigInteger('collector')->default(0);
            $table->bigInteger('realCollector')->default(0);
            $table->float('mass')->default(0);
            $table->float('massUnround')->default(0);
            $table->string('flag')->default('n');
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
        Schema::dropIfExists('internal_post_order');
    }
}
