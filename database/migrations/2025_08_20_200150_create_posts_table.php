<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->unsignedBigInteger('area_id')->index('posts_area_id_foreign_key');
            $table->unsignedBigInteger('user_id')->index('posts_user_id_foreign_key');
            $table->unsignedBigInteger('category_id')->index('posts_category_id_foreign_key');
            $table->string('title');
            $table->string('slug');
            $table->enum('sort', ['عمل قائم', 'فكرة']);
            $table->text('partner_sort');
            $table->string('partnership_percentage', 191)->nullable();
            $table->integer('weeks_hours')->nullable();
            $table->decimal('price', 10);
            $table->integer('partners_no');
            $table->longText('body');
            $table->string('phone');
            $table->string('email')->default('1');
            $table->string('img')->nullable();
            $table->boolean('blacklist')->default(false);
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
        Schema::dropIfExists('posts');
    }
}
