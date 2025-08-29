<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckFeaturedPosts extends Command
{
    protected $signature = 'posts:check-featured';
    protected $description = 'التحقق من الإعلانات المميزة وإلغاء تمييز التي تجاوزت المدة المحددة';

    public function handle()
    {
        $this->info('جاري التحقق من الإعلانات المميزة...');

        // البحث عن الإعلانات المميزة التي تجاوزت المدة المحددة
        $expiredPosts = Post::where('is_featured', true)
            ->where('featured_until', '<', Carbon::now())
            ->get();

        foreach ($expiredPosts as $post) {
            // إلغاء تمييز الإعلان
            $post->update([
                'is_featured' => false,
                'featured_until' => null
            ]);

            $this->info("تم إلغاء تمييز الإعلان #{$post->id}");
        }

        $this->info("تم الانتهاء. تم إلغاء تمييز {$expiredPosts->count()} إعلان.");
    }
}
