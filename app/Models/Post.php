<?php

namespace App\Models;

use App\Filters\BaseFilter;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    use HasTags;

    protected $fillable = [
        'code',
        'area_id',
        'user_id',
        'category_id',
        'ad_type_id',
        'title',
        'slug',
        'sort',
        'partner_sort',
        'partnership_percentage',
        'weeks_hours',
        'price',
        'partners_no',
        'body',
        'phone',
        'email',
        'img',
        'blacklist',
        'is_paid',
        'duration_days',
        'features',
        'status',
        'starts_at',
        'ends_at',
        'pinned_at',
        'is_featured',
        'featured_until'
    ];
    protected $guarded = [];

    protected $casts = [
        'features' => 'array',
        'pricing_snapshot' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'pinned_at' => 'datetime',
        'blacklist' => 'boolean',
        'is_paid' => 'boolean',
        'is_featured' => 'boolean',
        'featured_until' => 'datetime',
    ];

    public function adType()
    {
        return $this->belongsTo(AdType::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function latestPayment()
    {
        return $this->morphOne(Payment::class, 'payable')->latestOfMany();
    }

    // Scopes مفيدة
    public function scopeActive($q)
    {
        return $q->where('status', 'active')
            ->where(function ($qq) {
                $qq->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public function scopeFeatured($q)
    {
        return $q->where('is_featured', true)
            ->where(function ($query) {
                $query->whereNull('featured_until')
                    ->orWhere('featured_until', '>', now());
            })
            ->latest();
    }

    public function scopeHomeFeatured($q)
    {
        return $q->featured()->limit(4);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id')->whereLike(1);
    }

    public function dislikes()
    {
        return $this->hasMany(Like::class, 'post_id')->where('like', '!=', 1);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function getImgPathAttribute()
    {
        return asset('storage/main/posts/' . $this->img);
    }

    public static function scopeFilter(Builder $builder, $filters)
    {
        return (new BaseFilter(request()))->apply($builder, $filters);
    }

    //Count Categories
    public static function CountCategories()
    {
        $categories = Category::whereCategoryId(null)->get();
        $countCategories = [];

        foreach ($categories as $key => $value) {
            $key = $value->name;
            $countCategories[$key] = [];
            //$countCategories[$key] = count($value->posts);
            $countCategories[$key] = rand(0, 100);
        }

        return $countCategories;
    }

    //Count Posts By Month
    public static function FindPostsByMonth($categoryName, $monthNumber)
    {
        $category = Category::whereName($categoryName)->first();
        //return Self::whereYear('created_at' , Carbon::now()->year)->whereMonth('created_at' , $monthNumber)->whereCategoryId($category->id)->count();
        //return self::whereYear('created_at' , Carbon::now()->year)->whereMonth('created_at' , $monthNumber)->get();
        return rand(0, 50);
    }

    // التحقق من حالة الإعلان المميز
    public function isFeaturedActive(): bool
    {
        if (!$this->is_featured) {
            return false;
        }

        if ($this->featured_until && $this->featured_until->isPast()) {
            // إذا انتهت مدة التمييز، نقوم بإلغاء التمييز تلقائياً
            $this->update([
                'is_featured' => false,
                'featured_until' => null
            ]);
            return false;
        }

        return true;
    }

    // التحقق من إمكانية إضافة إعلان مميز جديد
    public static function canAddNewFeatured(): bool
    {
        $currentFeaturedCount = self::featured()->count();
        return $currentFeaturedCount < 4;
    }

    // الحصول على المدة القصوى للتمييز بالأيام
    public static function getMaxFeatureDuration(): int
    {
        return 90; // 3 شهور
    }

    // this is a recommended way to declare event handlers
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            $post->attachments()->delete();
            $post->likes()->delete();
            $post->dislikes()->delete();
            $post->reports()->delete();
            $post->likes()->delete();
            $post->dislikes()->delete();
            $post->reports()->delete();
        });
    }


}
