<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckAndMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:check-and-migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'فحص الجداول الموجودة وإنشاء المفقودة فقط';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 فحص الجداول الموجودة...');

        // قائمة الجداول المطلوبة
        $requiredTables = [
            'users' => $this->createUsersTableIfNotExists(),
            'posts' => $this->createPostsTableIfNotExists(),
            'categories' => $this->createCategoriesTableIfNotExists(),
            'areas' => $this->createAreasTableIfNotExists(),
            'payments' => $this->createPaymentsTableIfNotExists(),
            'commission_payments' => $this->createCommissionPaymentsTableIfNotExists(),
            'contacts' => $this->createContactsTableIfNotExists(),
            'settings' => $this->createSettingsTableIfNotExists(),
            'pages' => $this->createPagesTableIfNotExists(),
            'admins' => $this->createAdminsTableIfNotExists(),
        ];

        foreach ($requiredTables as $tableName => $createFunction) {
            if (Schema::hasTable($tableName)) {
                $this->line("✅ {$tableName} - موجود");
            } else {
                $this->info("🔧 إنشاء جدول {$tableName}...");
                $createFunction();
                $this->info("✅ تم إنشاء {$tableName}");
            }
        }

        $this->info('🎉 تم الانتهاء من فحص جميع الجداول!');
        return 0;
    }

    private function createUsersTableIfNotExists()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('phone')->nullable();
                $table->timestamp('phone_verified_at')->nullable();
                $table->string('city')->nullable();
                $table->date('birth_date')->nullable();
                $table->decimal('max_budget', 10, 2)->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    private function createPostsTableIfNotExists()
    {
        if (!Schema::hasTable('posts')) {
            Schema::create('posts', function ($table) {
                $table->id();
                $table->string('code')->nullable();
                $table->foreignId('area_id')->nullable();
                $table->foreignId('user_id');
                $table->foreignId('category_id');
                $table->string('title');
                $table->string('slug')->nullable();
                $table->enum('sort', ['asc', 'desc'])->default('asc');
                $table->text('partner_sort')->nullable();
                $table->string('partnership_percentage')->nullable();
                $table->integer('weeks_hours')->nullable();
                $table->decimal('price', 10, 2);
                $table->boolean('is_paid')->default(false);
                $table->integer('duration_days')->nullable();
                $table->json('features')->nullable();
                $table->json('pricing_snapshot')->nullable();
                $table->foreignId('payment_id')->nullable();
                $table->integer('partners_no');
                $table->longText('body');
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('img')->nullable();
                $table->boolean('blacklist')->default(false);
                $table->enum('status', ['active', 'pending', 'expired', 'pending_payment'])->default('active');
                $table->boolean('is_featured')->default(false);
                $table->timestamp('featured_until')->nullable();
                $table->integer('featured_rank')->nullable();
                $table->timestamps();
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->timestamp('pinned_at')->nullable();
            });
        }
    }

    private function createCategoriesTableIfNotExists()
    {
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->nullable();
                $table->string('img')->nullable();
                $table->text('description')->nullable();
                $table->integer('position')->default(0);
                $table->timestamps();
            });
        }
    }

    private function createAreasTableIfNotExists()
    {
        if (!Schema::hasTable('areas')) {
            Schema::create('areas', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->nullable();
                $table->foreignId('parent_id')->nullable();
                $table->integer('position')->default(0);
                $table->timestamps();
            });
        }
    }

    private function createPaymentsTableIfNotExists()
    {
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function ($table) {
                $table->id();
                $table->foreignId('user_id');
                $table->string('gateway');
                $table->decimal('amount', 10, 2);
                $table->string('currency', 3)->default('SAR');
                $table->enum('status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending');
                $table->string('gateway_order_id')->nullable();
                $table->string('gateway_transaction_id')->nullable();
                $table->string('gateway_reference')->nullable();
                $table->text('description')->nullable();
                $table->string('return_url')->nullable();
                $table->string('cancel_url')->nullable();
                $table->morphs('payable');
                $table->timestamps();
            });
        }
    }

    private function createCommissionPaymentsTableIfNotExists()
    {
        if (!Schema::hasTable('commission_payments')) {
            Schema::create('commission_payments', function ($table) {
                $table->id();
                $table->foreignId('user_id');
                $table->decimal('amount', 10, 2);
                $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    private function createContactsTableIfNotExists()
    {
        if (!Schema::hasTable('contacts')) {
            Schema::create('contacts', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('mobile');
                $table->text('body');
                $table->timestamps();
            });
        }
    }

    private function createSettingsTableIfNotExists()
    {
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('telephone')->nullable();
                $table->string('email')->nullable();
                $table->text('address')->nullable();
                $table->timestamps();
            });
        }
    }

    private function createPagesTableIfNotExists()
    {
        if (!Schema::hasTable('pages')) {
            Schema::create('pages', function ($table) {
                $table->id();
                $table->string('title');
                $table->string('slug');
                $table->longText('body');
                $table->timestamps();
            });
        }
    }

    private function createAdminsTableIfNotExists()
    {
        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }
}
