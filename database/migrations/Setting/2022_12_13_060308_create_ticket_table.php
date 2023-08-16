<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('userId')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->string('family')->nullable();
            $table->string('mobile')->nullable();
            $table->bigInteger('numberParcel')->nullable();
            $table->bigInteger('partnumber')->nullable();
            $table->string('typeTicket')->nullable();
            $table->string('subject')->nullable();
            $table->string('Handlingunit')->nullable();
            $table->string('importance')->nullable();
            $table->text('message');
            $table->text('answer')->nullable();
            $table->string('status')->default('پاسخ داده نشده');
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
        Schema::dropIfExists('ticket');
    }
}
