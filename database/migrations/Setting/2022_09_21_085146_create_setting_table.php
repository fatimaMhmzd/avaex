<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('value');
            $table->timestamps();
        });
        DB::table('setting')->insert(array(
            array('key' => 'لوگو', 'value' => '/LOGO.png' ),
            array('key' => 'ایمیل', 'value' => 'pishrotarabar@yahoo.com'),
            array('key' => 'فاکس', 'value' => '09053169105'  ),
            array('key' => 'نام کمپانی', 'value' => 'پستاپ'  ),
            array('key' => 'درباره کمپانی', 'value' => 'پستآپ برای خدمات ارزشمندی که شایسته آنید'  ),
            array('key' => 'آدرس', 'value' => 'استان خراسان رضوی مشهد بخش شاندیز شهرک صنعتی طوس بلوار فناوری شهرک بیوتکنولوژی پلاک یک'  ),
            array('key' => 'کد پستی', 'value' => '۹۱۸۵۱۷۱۱۳۵'  ),
            array('key' => 'ساعت کاری', 'value' => '۸ تا ۱۶'  ),
            array('key' => 'شماره تماس 1', 'value' => '۰۵۱۳۷۲۷۵۳۲۷' ),
            array('key' => 'شماره تماس 2', 'value' => '۰۹۱۵۷۰۷۶۵۵۲' ),
            array('key' => 'شماره تماس 3', 'value' => '۰۹۰۵۳۱۶۹۱۰۵' ),
            array('key' => 'شماره تماس 4', 'value' => '09053169105' ),




        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting');
    }
}
