<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('base_price', 10, 2)->default(0);
            $table->boolean('is_paid')->default(false);
            $table->unsignedInteger('duration_days')->default(7);
            $table->json('features')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->string('description')->nullable();
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
        Schema::dropIfExists('ad_types');
    }
};
