<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSliderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slider', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('page_id')->unsigned();
            $table->foreign('page_id')
                ->references('id')->on('pages')
                ->onDelete('cascade');
            $table->string('image');
            $table->string('imageApp');
            $table->string('alt') -> nullable();
            $table->string('title') -> nullable();
            $table->timestamps();
        });

        for( $i= 1; $i<=20; $i++){
            DB::table('slider')->insert(
                array('image' => 'http://192.168.137.1:8080/common/951151663747872.jpg' , 'page_id' => $i ),

            );


    }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slider');
    }
}
