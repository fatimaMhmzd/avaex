<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->text('seoDescription')->nullable();
            $table->text('seoKeyboard')->nullable();
            $table->bigInteger('groupId')->unsigned();
            $table->foreign('groupId')
                ->references('id')->on('blog_groups')
                ->onDelete('no action');
            $table->string('image');
            $table->longText('longDescription');
            $table->text('shortDescription');
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
        Schema::dropIfExists('blogs');
    }
}
