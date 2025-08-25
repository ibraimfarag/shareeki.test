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
        Schema::create('ad_pricing_rules', function (Blueprint $table) {
                   $table->id();
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->unsignedBigInteger('ad_type_id')->nullable()->index();
            $table->enum('duration_unit', ['day','week','month']);
            $table->decimal('multiplier', 8, 4)->default(1.0000);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['category_id','ad_type_id','duration_unit']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ad_pricing_rules');
    }
};
