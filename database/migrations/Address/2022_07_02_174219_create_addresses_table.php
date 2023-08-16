<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('userId')->unsigned();
            $table->foreign('userId')
                ->references('id')->on('users')
                ->onDelete('no action');
            $table->bigInteger('cityId')->unsigned();
            $table->foreign('cityId')
                ->references('id')->on('cities')
                ->onDelete('no action');
            $table->bigInteger('provinceId')->unsigned();
            $table->foreign('provinceId')
                ->references('id')->on('provinces')
                ->onDelete('no action');
            $table->bigInteger('countryId')->unsigned();
            $table->foreign('countryId')
                ->references('id')->on('countries')
                ->onDelete('no action');
            $table->bigInteger('areaId')->unsigned();
            $table->text('address');
            $table->string('postCode');
            $table->string('name')->nullable();
            $table->string('family')->nullable();
            $table->string('tel')->nullable();
            $table->string('email')->nullable();
            $table->string('compony')->nullable();
            $table->text('totalAddress');
            $table->longText('description')->nullable();
            $table->longText('enDescription')->nullable();
            $table->boolean('Default')->default(false);
            $table->string('nationalCode')->unique();
            $table->boolean('type')->default(false);
            $table->boolean('senderOrgetter')->default(false);
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
        Schema::dropIfExists('addresses');
    }
}
