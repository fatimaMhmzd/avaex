<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('userId')->unsigned();
            $table->foreign('userId')
                ->references('id')->on('users')
                ->onDelete('no action');
            $table->bigInteger('countryId')->unsigned()->default(1);
            $table->foreign('countryId')
                ->references('id')->on('countries')
                ->onDelete('no action');
            $table->bigInteger('provinceId')->unsigned();
            $table->foreign('provinceId')
                ->references('id')->on('province')
                ->onDelete('no action');
            $table->bigInteger('cityId')->unsigned();
            $table->foreign('cityId')
                ->references('id')->on('city')
                ->onDelete('no action');
            $table->text('address');
            $table->string('major')->nullable();
            $table->string('degree')->nullable();
            $table->string('tel')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('married')->nullable();
            $table->string('age')->nullable();
            $table->text('description')->nullable();
            $table->string('military')->nullable();
            $table->boolean('canUpdate')->default(false);
            $table->string('havingOffice')->nullable();
            $table->text('relatedExperience')->nullable();
            $table->string('equipAbility')->nullable();
            $table->string('salaryAbility')->nullable();
            $table->string('acquaintance')->nullable();
            $table->string('guaranty')->nullable();
            $table->string('gender')->nullable();
            $table->string('status');

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
        Schema::dropIfExists('agent');
    }
}
