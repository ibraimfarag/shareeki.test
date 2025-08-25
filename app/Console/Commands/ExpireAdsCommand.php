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

        $affectedExact = Post::query()
            ->where('status', 'active')
            ->where('is_paid', true)
            ->whereNotNull('ends_at')
            ->where('ends_at', '<', $now)
            ->update([
                'status'        => 'expired',
                'pinned_at'     => null,    
                'featured_rank' => null,    
            ]);

        // $affectedDateOnly = Post::query()
        //     ->where('status', 'active')
        //     ->where('is_paid', true)
        //     ->whereNotNull('ends_at')
        //     ->whereDate('ends_at', '=', $today->toDateString())
        //     ->where('ends_at', '<', $now) // يضمن مرور بداية اليوم
        //     ->update([
        //         'status'        => 'expired',
        //         'pinned_at'     => null,
        //         'featured_rank' => null,
        //     ]);

        // $this->info("Expired updated - exact: {$affectedExact}, date-only: {$affectedDateOnly}");

        return self::SUCCESS;
    }
}
