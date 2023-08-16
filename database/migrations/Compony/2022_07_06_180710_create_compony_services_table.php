<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComponyServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compony_services', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('componyId')->unsigned();
            $table->foreign('componyId')
                ->references('id')->on('componies')
                ->onDelete('no action');
            $table->bigInteger('componyTypeId')->unsigned();
            $table->foreign('componyTypeId')
                ->references('id')->on('compony_type_post')
                ->onDelete('no action');
            $table->bigInteger('serviceId')->unsigned();
            $table->foreign('serviceId')
                ->references('id')->on('services')
                ->onDelete('no action');
            $table->bigInteger('codeOrder')->nullable();
            $table->bigInteger('onlineOrder')->nullable();
            $table->bigInteger('pastOrder')->nullable();
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
        Schema::dropIfExists('compony_services');
    }
}
