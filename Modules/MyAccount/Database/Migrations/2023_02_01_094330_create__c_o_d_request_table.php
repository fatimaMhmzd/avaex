<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCODRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('_c_o_d_request', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('userId')->unsigned();
            $table->string('tel');
            $table->string('nationalCode')->nullable();
            $table->string('serial')->nullable();
            $table->string('postCode')->nullable();
            $table->date('birthday')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('accountNumber')->nullable();
            $table->string('shabaNumber')->nullable();
            $table->string('bankBranchName')->nullable();
            $table->bigInteger('cityId')->unsigned();
            $table->bigInteger('provinceId')->unsigned();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('_c_o_d_request');
    }
}
