<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;

class ExpireAdsCommand extends Command
{
    protected $signature = 'ads:expire';
    protected $description = 'تحديث حالة الإعلانات المنتهية إلى expired وتعطيل كونها مدفوعة دون حذف';

    public function handle(): int
    {
        $now   = now();
        $today = $now->clone()->startOfDay();


        return self::SUCCESS;
    }
}
