<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code');
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->bigInteger('min_value')->nullable();
            $table->bigInteger('max_value')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->bigInteger('user_limit_number')->nullable();
            $table->bigInteger('usage_count')->default(0);
            $table->boolean('status')->default(0);
            $table->boolean('groupy')->default(0);
            $table->boolean('allAgent')->default(0);
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
        Schema::dropIfExists('discounts');
    }
}
