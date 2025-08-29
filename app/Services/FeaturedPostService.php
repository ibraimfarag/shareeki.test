<?php

namespace App\Services;

use App\Models\Post;
use Carbon\Carbon;

class FeaturedPostService
{
    public const FEATURE_PRICE = 149.50;
    public const FEATURE_DURATION_MONTHS = 3;
    public const MAX_FEATURED_POSTS = 4;

    public function canFeaturePost(): bool
    {
        // التحقق من عدد الإعلانات المميزة النشطة
        $activeFeaturedCount = Post::featured()->count();

        // لا يمكن تجاوز الحد الأقصى للإعلانات المميزة
        if ($activeFeaturedCount >= self::MAX_FEATURED_POSTS) {
            return false;
        }

        return true;
    }

    public function validateFeatureDuration(): bool
    {
        // التأكد من أن مدة التمييز لا تتجاوز 3 أشهر
        return self::FEATURE_DURATION_MONTHS <= (Post::getMaxFeatureDuration() / 30);
    }

    public function featurePost(Post $post): bool
    {
        if (!$this->canFeaturePost()) {
            return false;
        }

        $post->update([
            'is_featured' => true,
            'featured_until' => Carbon::now()->addMonths(self::FEATURE_DURATION_MONTHS),
        ]);

        return true;
    }

    public function getFeaturePrice(): float
    {
        return self::FEATURE_PRICE;
    }

    public function getFeatureDuration(): int
    {
        return self::FEATURE_DURATION_MONTHS;
    }

    public function unfeaturePost(Post $post): void
    {
        $post->update([
            'is_featured' => false,
            'featured_until' => null,
        ]);
    }

    public function getFeaturedPosts()
    {
        return Post::homeFeatured()->get();
    }
}
