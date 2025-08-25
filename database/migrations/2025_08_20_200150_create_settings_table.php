<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('logo1');
            $table->string('logo2');
            $table->string('telephone');
            $table->string('email');
            $table->text('about');
            $table->string('facebook');
            $table->string('googleplus');
            $table->string('youtube');
            $table->string('twitter');
            $table->string('telegram');
            $table->string('whatsapp');
            $table->string('snapchat');
            $table->string('linkedin');
            $table->string('play_store');
            $table->string('app_store');
            $table->string('microsoft_store');
            $table->string('qr_code');
            $table->decimal('commission_percentage', 7, 3);
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
        Schema::dropIfExists('settings');
    }
}
