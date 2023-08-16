<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateComponyTypePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compony_type_post', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('chaparId');
            $table->timestamps();
        });
        DB::table('compony_type_post')->insert(array(
            array('name' => 'زمینی' , 'chaparId' => 1),
            array('name' => 'هوایی' , 'chaparId' => 6),
            array('name' => 'پستی' , 'chaparId' => 11),
            array('name' => 'چاپار پلاس' , 'chaparId' => 35),
            array('name' => 'پاکت' , 'chaparId' => 97),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compony_type__post');
    }
}
