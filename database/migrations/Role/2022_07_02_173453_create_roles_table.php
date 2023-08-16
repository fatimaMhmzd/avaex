<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->boolean('can_delete')->default(true);
            $table->boolean('can_update')->default(true);
            $table->timestamps();
        });
        DB::table('roles')->insert(array(
            array('title' => 'customer' , 'can_delete' => 'false' , 'can_update' => 'false'),
            array('title' => 'admin' , 'can_delete' => 'false' , 'can_update' => 'false'),
            array('title' => 'agent', 'can_delete' => 'false' , 'can_update' => 'false'),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
