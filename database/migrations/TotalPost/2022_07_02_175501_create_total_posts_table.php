<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTotalPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('total_posts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('numberParcel');
            $table->string('serviceNumberParcel')->nullable();
            $table->string('serviceUuid')->nullable();
            $table->string('barCode')->nullable();
            $table->bigInteger('agentId')->unsigned();
            $table->bigInteger('userId')->unsigned();
            $table->foreign('userId')
                ->references('id')->on('users')
                ->onDelete('no action');
            $table->bigInteger('addressId')->unsigned();
            $table->foreign('addressId')
                ->references('id')->on('addresses')
                ->onDelete('no action');
            $table->bigInteger('getterAddressId')->unsigned();
            $table->foreign('getterAddressId')
                ->references('id')->on('addresses')
                ->onDelete('no action');
            $table->bigInteger('typeSerId')->unsigned();
            $table->bigInteger('collector')->default(0);
            $table->bigInteger('componyId')->unsigned();
            $table->bigInteger('componyTypeId')->unsigned();
            $table->bigInteger('componyServicesId')->unsigned();
            $table->string('status');
            $table->bigInteger('printFactor')->default(0);
            $table->string('discountCouponCode')->nullable();
            $table->bigInteger('hasNotifRequest')->default(0);
            $table->boolean('RequestPrintAvatar')->nullable();
            $table->bigInteger('Freight')->default(0);
            $table->bigInteger('TAX')->default(0);
            $table->bigInteger('Payable')->default(0);
            $table->bigInteger('realPayable')->default(0);
            $table->bigInteger('Packaging')->default(0);
            $table->bigInteger('ServicesAt ')->default(0);
            $table->bigInteger('serviceInPlace ')->default(0);
            $table->bigInteger('Insurance ')->default(0);
            $table->bigInteger('amountServices ')->default(0);
            $table->smallInteger('allForPostup')->default(0);
            $table->smallInteger('byAgent')->default(0);
            $table->string('MethodPayment')->nullable();
            $table->string('factorstatus')->nullable();
            $table->text('description')->nullable();

            $table->integer('totalNumber')->default(0);
            $table->integer('isAfterRent')->nullable();
            $table->integer('isCod')->nullable();
            $table->float('totalCollectiveWeight')->default(0);
            $table->float('totalGrossWeight')->default(0);
            $table->float('totalWeightPayable')->default(0);
            $table->float('totalCost')->default(0);
            $table->float('amountCOD')->default(0);
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
        Schema::dropIfExists('total_posts');
    }
}
