<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SafeMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:safe {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'تشغيل migrations بأمان - يتجاهل الجداول الموجودة بالفعل';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('بدء تشغيل Safe Migration...');

        try {
            // التحقق من وجود جدول migrations
            if (!Schema::hasTable('migrations')) {
                $this->info('إنشاء جدول migrations...');
                Artisan::call('migrate:install');
            }

            // تشغيل migrations مع تجاهل الأخطاء
            $this->info('تشغيل migrations...');
            
            $exitCode = Artisan::call('migrate', [
                '--force' => $this->option('force')
            ]);

            if ($exitCode === 0) {
                $this->info('✅ تم تشغيل جميع migrations بنجاح!');
            } else {
                $this->warn('⚠️ بعض migrations قد تحتاج مراجعة.');
            }

            // عرض حالة migrations
            $this->info('حالة migrations الحالية:');
            Artisan::call('migrate:status');
            $this->line(Artisan::output());

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ حدث خطأ: ' . $e->getMessage());
            
            // محاولة تشغيل migrations فردياً
            $this->info('محاولة تشغيل migrations فردياً...');
            $this->runMigrationsIndividually();
            
            return 1;
        }
    }

    /**
     * تشغيل migrations فردياً مع تجاهل الأخطاء
     */
    private function runMigrationsIndividually()
    {
        $migrationFiles = glob(database_path('migrations/*.php'));
        
        foreach ($migrationFiles as $file) {
            $filename = basename($file, '.php');
            
            try {
                // التحقق من أن migration لم يتم تشغيله من قبل
                $exists = DB::table('migrations')
                    ->where('migration', $filename)
                    ->exists();
                
                if (!$exists) {
                    $this->info("تشغيل: {$filename}");
                    Artisan::call('migrate', [
                        '--path' => 'database/migrations/' . basename($file),
                        '--force' => true
                    ]);
                    $this->info("✅ تم بنجاح: {$filename}");
                } else {
                    $this->line("⏭️ موجود بالفعل: {$filename}");
                }
                
            } catch (\Exception $e) {
                $this->warn("⚠️ تم تجاهل: {$filename} - {$e->getMessage()}");
                continue;
            }
        }
    }
}
