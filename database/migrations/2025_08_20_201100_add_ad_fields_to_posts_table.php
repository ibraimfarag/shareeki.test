<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('ad_type_id')->nullable()->after('category_id');
            
            $table->boolean('is_paid')->default(false)->after('price');
            $table->unsignedInteger('duration_days')->default(7)->after('is_paid');
            $table->json('features')->nullable()->after('duration_days');
            
            $table->enum('status', ['draft', 'pending_payment', 'active', 'expired', 'rejected'])
                  ->default('draft')->after('blacklist');
            $table->timestamp('starts_at')->nullable()->after('updated_at');
            $table->timestamp('ends_at')->nullable()->after('starts_at');
            $table->timestamp('pinned_at')->nullable()->after('ends_at');
            $table->unsignedInteger('featured_rank')->nullable()->after('pinned_at');
            
            $table->json('pricing_snapshot')->nullable()->after('features');
            $table->foreignId('payment_id')->nullable()->after('pricing_snapshot');
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
                       $table->dropColumn([
                'ad_type_id','is_paid','duration_days','features',
                'status','starts_at','ends_at','pinned_at','featured_rank',
                'pricing_snapshot','payment_id'
            ]);

        });
    }
};
