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
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('city', 191)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('mobile', 191)->nullable();
            $table->bigInteger('max_budget')->nullable();
            $table->tinyInteger('allow_recommendation_page')->default(0);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->string('google_id', 200)->nullable();
        });
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
