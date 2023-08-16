<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('userId')->unsigned();
            $table->bigInteger('amount');
            $table->bigInteger('blockAmount')->nullable();
            $table->bigInteger('cash');
            $table->string('type');
            $table->string('trackingCode')->nullable();
            $table->string('status');
            $table->text('description')->nullable();
            $table->smallInteger('isAgent')->nullable();
            $table->bigInteger('fatherId')->unsigned();
            $table->bigInteger('numberParcel')->unsigned()->nullable();
            $table->smallInteger('isBlock')->default(0);
            $table->string('serviceType')->default('پیشکرایه');
            $table->smallInteger('fromAccount')->default(0);
            $table->smallInteger('toAccount')->default(0);
            $table->smallInteger('discountCodeId')->default(0);
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
        Schema::dropIfExists('wallet');
    }
}
