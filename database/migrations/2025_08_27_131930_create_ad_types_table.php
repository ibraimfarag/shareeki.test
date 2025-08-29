<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ad_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('base_price', 10, 2)->default(0);
            $table->integer('duration_days')->default(30);
            $table->text('description')->nullable();
            $table->boolean('is_paid')->default(true);
            $table->boolean('is_recurring')->default(false);
            $table->json('features')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_types');
    }
};
