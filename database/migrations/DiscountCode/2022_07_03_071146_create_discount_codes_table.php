<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('userId')->unsigned();
            $table->foreign('userId')
                ->references('id')->on('users')
                ->onDelete('no action');
            $table->bigInteger('IdentificationCode')->unsigned();
            $table->foreign('IdentificationCode')
                ->references('id')->on('users')
                ->onDelete('no action');
            $table->string('code');
            $table->integer('price');
            $table->string('type')->nullable();
            $table->string('status')->default('not used');
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
        Schema::dropIfExists('discount_codes');
    }
}
