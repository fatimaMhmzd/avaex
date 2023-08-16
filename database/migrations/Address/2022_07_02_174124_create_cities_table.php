<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('faName');
            $table->string('enName')->nullable();
            $table->bigInteger('provinceId')->unsigned();
            $table->foreign('provinceId')
                ->references('id')->on('provinces')
                ->onDelete('no action');
            $table->bigInteger('countryId')->unsigned();
            $table->boolean('hasAgent')->default(false);
            $table->softDeletes();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
