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
        Schema::table('ad_pricing_rules', function (Blueprint $table) {
           
            $table->integer('min_duration')->default(1)->after('duration_unit');
            $table->integer('max_duration')->nullable()->after('min_duration');
            
            $table->string('rule_name')->nullable()->after('id');
            
            $table->integer('priority')->default(0)->after('active');
            
            $table->index(['duration_unit', 'min_duration', 'max_duration'], 'duration_range_index');
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
               Schema::table('ad_pricing_rules', function (Blueprint $table) {
            $table->dropIndex('duration_range_index');
            $table->dropColumn(['min_duration', 'max_duration', 'rule_name', 'priority']);
        });

    }
};
