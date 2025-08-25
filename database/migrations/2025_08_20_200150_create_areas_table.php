<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('parent_id', 4)->nullable();
            $table->string('english', 29)->nullable();
            $table->string('name', 24)->nullable();
            $table->string('latitude', 7)->nullable();
            $table->string('longitude', 7)->nullable();
            $table->smallInteger('left')->nullable();
            $table->smallInteger('right')->nullable();
            $table->integer('position')->default(7);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('areas');
    }
}
