<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->foreign(['area_id'], 'posts_area_id_foreign_key')->references(['id'])->on('areas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['category_id'], 'posts_category_id_foreign_key')->references(['id'])->on('categories')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['user_id'], 'posts_user_id_foreign_key')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign('posts_area_id_foreign_key');
            $table->dropForeign('posts_category_id_foreign_key');
            $table->dropForeign('posts_user_id_foreign_key');
        });
    }
}
