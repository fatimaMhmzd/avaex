<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('agentId');
            $table->string("mobile");
            $table->string("carMeli")->nullable();
            $table->string("certificate")->nullable();
            $table->string("carKart")->nullable();
            $table->string("sooPishine")->nullable();
            $table->string("contract")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('driverItem', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('driverId');
            $table->bigInteger('itemId');
            $table->string("status");
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
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('driverItem');
    }
}
