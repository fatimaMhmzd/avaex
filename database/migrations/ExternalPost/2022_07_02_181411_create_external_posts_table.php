<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_posts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('totalPostId')->unsigned();
            $table->bigInteger('typeSerId')->unsigned()->nullable();
            $table->bigInteger('componyId')->unsigned()->nullable();
            $table->bigInteger('componyTypeId')->unsigned()->nullable();
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
        Schema::dropIfExists('external_posts');
    }
}
