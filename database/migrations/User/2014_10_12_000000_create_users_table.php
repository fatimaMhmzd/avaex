<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('family')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone');
            $table->string('nationalCode')->unique();
            $table->string('code')->default(0);
            $table->string('email')->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('IsAdmin')->default(false);
            $table->boolean('IsAgent')->default(false);
            $table->boolean('type')->default(false);
            $table->string('registrNumber')->nullable();
            $table->bigInteger('wallet')->default(0);
            $table->bigInteger('blockWallet')->default(0);
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert(array(
            array('id'=>'1','name' => 'پست' , 'password' => 'پست'  , 'phone'=> 'پست' , 'nationalCode'=> 'پست','type'=>'1'),
            array('id'=>'2','name' => 'ماهکس' , 'password' => 'ماهکس'  , 'phone'=> 'ماهکس' , 'nationalCode'=> 'ماهکس','type'=>'1'),
            array('id'=>'3','name' => 'چاپار' , 'password' => 'چاپار'  , 'phone'=> 'چاپار' , 'nationalCode'=> 'چاپار','type'=>'1'),
            array('id'=>'4','name' => 'تست1' , 'password' => 'تست1'  , 'phone'=> 'تست1' , 'nationalCode'=> 'تست1','type'=>'1'),
            array('id'=>'5','name' => 'تست2' , 'password' => 'تست2'  , 'phone'=> 'تست2' , 'nationalCode'=> 'تست2','type'=>'1'),
            array('id'=>'6','name' => 'تست3' , 'password' => 'تست3'  , 'phone'=> 'تست3' , 'nationalCode'=> 'تست3','type'=>'1'),
            array('id'=>'7','name' => 'تست4' , 'password' => 'تست4'  , 'phone'=> 'تست4' , 'nationalCode'=> 'تست4','type'=>'1'),
            array('id'=>'8','name' => 'تست5' , 'password' => 'تست5'  , 'phone'=> 'تست5' , 'nationalCode'=> 'تست5','type'=>'1'),
            array('id'=>'9','name' => 'تست6' , 'password' => 'تست6'  , 'phone'=> 'تست6' , 'nationalCode'=> 'تست6','type'=>'1'),
            array('id'=>'10','name' => 'تست7' , 'password' => 'تست7'  , 'phone'=> 'تست7' , 'nationalCode'=> 'تست7','type'=>'1'),
            array('id'=>'11','name' => 'تست8' , 'password' => 'تست8'  , 'phone'=> 'تست8' , 'nationalCode'=> 'تست8','type'=>'1'),
            array('id'=>'12','name' => 'تست9' , 'password' => 'تست9'  , 'phone'=> 'تست9' , 'nationalCode'=> 'تست9','type'=>'1'),
            array('id'=>'13','name' => 'تست10' , 'password' => 'تست10'  , 'phone'=> 'تست10' , 'nationalCode'=> 'تست10','type'=>'1'),
            array('id'=>'14','name' => 'تست11' , 'password' => 'تست11'  , 'phone'=> 'تست11' , 'nationalCode'=> 'تست11','type'=>'1'),
            array('id'=>'15','name' => 'پستاپ' , 'password' => 'پستاپ'  , 'phone'=> 'پستاپ' , 'nationalCode'=> 'پستاپ','type'=>'1'),
            array('id'=>'16','name' => 'کاربر ثبت تلفنی' , 'password' => '11111111'  , 'phone'=> '11111111' , 'nationalCode'=> '11111111','type'=>'1'),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
