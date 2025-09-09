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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->morphs('payable');
            $table->enum('gateway', ['rajhi']);
            $table->decimal('amount', 10, 2);
            $table->char('currency', 3)->default('SAR');
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded', 'canceled'])->default('pending');
            $table->string('gateway_order_id', 191)->unique();
            $table->string('gateway_ref', 191)->nullable()->unique();
            $table->json('response_payload')->nullable();
            $table->timestamp('paid_at')->nullable();
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
        Schema::dropIfExists('payments');
    }
};
