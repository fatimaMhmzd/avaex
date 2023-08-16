<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTermPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('term_payment', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->bigInteger('chaparId')->unsigned();
            $table->softDeletes();
            $table->timestamps();
        });
        DB::table('term_payment')->insert(array(
            array('title' => 'پیش کرایه' , 'chaparId' => 0),
            array('title' => 'پس کرایه' , 'chaparId' => 1),
            array('title' => 'COD' , 'chaparId' => 2),

        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('term_payment');
    }
}
