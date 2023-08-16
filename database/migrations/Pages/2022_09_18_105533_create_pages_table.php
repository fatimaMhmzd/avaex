<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->longText('descripthon')->nullable();
            $table->string('image')->nullable();
            $table->string('seo_key_word')->nullable();
            $table->string('seo_descript')->nullable();
            $table->boolean('is_special')->default(false);
            $table->timestamps();
        });

        DB::table('pages')->insert(array(
            array('title' => 'صفحه ی اصلی' , 'is_special' => 'true'),
            array('title' => 'پست داخلی', 'is_special' => 'true' ),
            array('title' => 'پشتیبانی', 'is_special' => 'true'),
            array('title' => 'پست خارجی'),
            array('title' => 'کیف پول'),
            array('title' => 'پیک شهری'),
            array('title' => 'بسته بندی'),
            array('title' => 'اپلیکیشن'),
            array('title' => 'درباره ی ما'),
            array('title' => 'اخذ نمایندگی'),
            array('title' => 'نشریه'),
            array('title' => 'فروشگاه'),
            array('title' => 'سفارش انبوه'),
            array('title' => 'پرداخت در محل'),
            array('title' => 'سرویس درب تا درب'),
            array('title' => 'پست اکسپرس'),
            array('title' => 'سفارش تلفنی'),
            array('title' => 'سفارش آنلاین'),
            array('title' => 'پس کرایه'),
            array('title' => 'پیش کرایه'),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
