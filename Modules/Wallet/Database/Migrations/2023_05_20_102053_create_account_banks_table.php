<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_banks', function (Blueprint $table) {
            $table->id();
            $table->integer('userId');
            $table->string('name')->nullable();
            $table->string('bankName')->nullable();
            $table->string('accountNumber')->nullable();
            $table->string('shaba')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        DB::table('account_banks')->insert(array(
            array('id'=>'1','userId'=>'1','name' => 'پست' , 'bankName' => 'ملت'  , 'shaba'=> '00000000000' , 'accountNumber'=> '00000000000'),
            array('id'=>'2','userId'=>'2','name' => 'ماهکس' , 'bankName' => 'ملت'  , 'shaba'=> '00000000000' , 'accountNumber'=> '00000000000'),
            array('id'=>'3','userId'=>'3','name' => 'چاپار' , 'bankName' => 'ملت'  , 'shaba'=> '00000000000' , 'accountNumber'=> '00000000000'),
            array('id'=>'4','userId'=>'4','name' => 'تست1' , 'bankName' => 'ملت'  , 'shaba'=> '00000000000' , 'accountNumber'=> '00000000000'),
            array('id'=>'5','userId'=>'5','name' => 'تست2' , 'bankName' => 'ملت'  , 'shaba'=> '00000000000' , 'accountNumber'=> '00000000000'),
            array('id'=>'6','userId'=>'6','name' => 'تست3' , 'bankName' => 'ملت'  , 'shaba'=> '00000000000' , 'accountNumber'=> '00000000000'),
            array('id'=>'7','userId'=>'7','name' => 'تست4' , 'bankName' => 'ملت'  , 'shaba'=> '00000000000' , 'accountNumber'=> '00000000000'),
            array('id'=>'8','userId'=>'8','name' => 'تست5' , 'bankName' => 'ملت'  , 'shaba'=> '00000000000' , 'accountNumber'=> '00000000000'),
            array('id'=>'9','userId'=>'9','name' => 'تست6' , 'bankName' => 'ملت'  , 'shaba'=> '00000000000' , 'accountNumber'=> '00000000000'),
            array('id'=>'10','userId'=>'10','name' => 'تست7' , 'bankName' => 'ملت'  , 'shaba'=> '00000000000' , 'accountNumber'=> '00000000000'),
            array('id'=>'11','userId'=>'11','name' => 'تست8' , 'bankName' => 'ملت'  , 'shaba'=> '00000000000' , 'accountNumber'=> '00000000000'),
            array('id'=>'12','userId'=>'12','name' => 'تست9' , 'bankName' => 'ملت'  , 'shaba'=> '00000000000' , 'accountNumber'=> '00000000000'),
            array('id'=>'13','userId'=>'13','name' => 'تست10' , 'bankName' => 'ملت'  , 'shaba'=> '00000000000' , 'accountNumber'=> '00000000000'),
            array('id'=>'14','userId'=>'14','name' => 'تست11' , 'bankName' => 'ملت'  , 'shaba'=> '00000000000' , 'accountNumber'=> '00000000000'),
            array('id'=>'15','userId'=>'15','name' => 'آواکس' , 'bankName' => 'ملت'  , 'shaba'=> '00000000000' , 'accountNumber'=> '00000000000'),

        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_banks');
    }
}
