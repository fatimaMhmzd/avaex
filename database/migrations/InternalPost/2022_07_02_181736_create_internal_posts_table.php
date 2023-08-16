<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_posts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('totalPostId')->unsigned();
            $table->foreign('totalPostId')
                ->references('id')->on('total_posts')
                ->onDelete('no action');
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
        Schema::dropIfExists('internal_posts');
    }
}
