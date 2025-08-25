<?php
// app/Services/Pricing/AdPricingService.php

namespace App\Services\Pricing;

use App\Models\Post;
use App\Models\AdType;
use App\Models\AdPricingRule;
use Carbon\Carbon;

class AdPricingService
{
    /**
     * حساب تسعيرة الإعلان
     */
    public function quote(Post $post, int $durationValue = null, string $durationUnit = null): array
    {
        $adType = AdType::findOrFail($post->ad_type_id);
        
        // إذا كان النوع مجاني
        if (!$adType->is_paid) {
            return $this->getFreeQuote($adType);
        }

        $duration = $durationValue ?? $adType->duration_days;
        $unit = $durationUnit ?? 'day';

        // البحث عن القاعدة المطبقة
        $applicableRule = $this->findApplicableRule(
            $post->category_id, 
            $post->ad_type_id, 
            $duration, 
            $unit
        );
        
        $multiplier = $applicableRule ? (float)$applicableRule->multiplier : 1.0;
        $basePrice = (float)$adType->base_price;
        
        // حساب التكلفة المفصلة
        $breakdown = $this->calculatePriceBreakdown($basePrice, $multiplier, $duration, $unit);
        
        return [
            'base_price' => $basePrice,
            'applicable_rule' => $applicableRule,
            'multiplier' => $multiplier,
            'duration' => ['value' => $duration, 'unit' => $unit],
            'total' => $breakdown['total'],
            'is_free' => false,
            'price_breakdown' => $breakdown,
            'rule_info' => $this->getRuleInfo($applicableRule)
        ];
    }

    /**
     * البحث عن القاعدة المطبقة للمدة المحددة
     */
    private function findApplicableRule($categoryId, $adTypeId, $duration, $unit)
    {
        return AdPricingRule::where('active', true)
            ->where('duration_unit', $unit)
            ->where('min_duration', '<=', $duration)
            ->where(function($query) use ($duration) {
                $query->whereNull('max_duration')
                      ->orWhere('max_duration', '>=', $duration);
            })
            ->where(function($query) use ($categoryId) {
                $query->whereNull('category_id')
                      ->orWhere('category_id', $categoryId);
            })
            ->where(function($query) use ($adTypeId) {
                $query->whereNull('ad_type_id')
                      ->orWhere('ad_type_id', $adTypeId);
            })
            // ترتيب للحصول على أكثر القواعد تحديداً وأولوية
            ->orderByRaw('CASE WHEN category_id IS NOT NULL THEN 2 ELSE 1 END DESC')
            ->orderByRaw('CASE WHEN ad_type_id IS NOT NULL THEN 2 ELSE 1 END DESC')
            ->orderBy('priority', 'desc')
            ->orderBy('multiplier', 'desc')
            ->first();
    }

    /**
     * حساب تفصيل السعر
     */
    private function calculatePriceBreakdown($basePrice, $multiplier, $duration, $unit)
    {
        // تحويل كل شيء لأيام للحساب الموحد
        $totalDays = $this->convertToDays($duration, $unit);
        
        // حساب السعر النهائي
        $dailyRate = $basePrice * $multiplier;
        $total = round($dailyRate * $totalDays, 2);
        
        return [
            'base_daily_price' => $basePrice,
            'multiplier' => $multiplier,
            'adjusted_daily_price' => $dailyRate,
            'total_days' => $totalDays,
            'duration_text' => $this->getDurationText($duration, $unit),
            'total' => $total,
            'savings' => $this->calculateSavings($basePrice, $dailyRate, $totalDays),
        ];
    }

    /**
     * حساب الوفورات أو الزيادة في السعر
     */
    private function calculateSavings($basePrice, $adjustedPrice, $totalDays)
    {
        $originalTotal = $basePrice * $totalDays;
        $adjustedTotal = $adjustedPrice * $totalDays;
        $difference = $originalTotal - $adjustedTotal;
        
        return [
            'amount' => abs($difference),
            'percentage' => $originalTotal > 0 ? round((abs($difference) / $originalTotal) * 100, 1) : 0,
            'type' => $difference > 0 ? 'saving' : ($difference < 0 ? 'increase' : 'none'),
            'text' => $this->getSavingsText($difference, $originalTotal)
        ];
    }

    /**
     * الحصول على نص الوفورات
     */
    private function getSavingsText($difference, $originalTotal)
    {
        if ($difference == 0) return 'سعر عادي';
        
        $percentage = round((abs($difference) / $originalTotal) * 100, 1);
        
        if ($difference > 0) {
            return "وفّر {$percentage}% (" . number_format(abs($difference), 2) . " ريال)";
        } else {
            return "زيادة {$percentage}% (" . number_format(abs($difference), 2) . " ريال)";
        }
    }

    /**
     * تحويل المدة إلى أيام
     */
    private function convertToDays($duration, $unit)
    {
        return match($unit) {
            'day' => $duration,
            'week' => $duration * 7,
            'month' => $duration * 30,
            'year' => $duration * 365,
            default => $duration,
        };
    }

    /**
     * الحصول على نص المدة
     */
    private function getDurationText($duration, $unit)
    {
        $unitText = match($unit) {
            'day' => $duration == 1 ? 'يوم' : 'أيام',
            'week' => $duration == 1 ? 'أسبوع' : 'أسابيع',
            'month' => $duration == 1 ? 'شهر' : 'أشهر',
            'year' => $duration == 1 ? 'سنة' : 'سنوات',
            default => $unit,
        };
        
        return $duration . ' ' . $unitText;
    }

    /**
     * معلومات القاعدة المطبقة
     */
    private function getRuleInfo($rule)
    {
        if (!$rule) return null;
        
        return [
            'name' => $rule->rule_name ?? 'قاعدة تسعير',
            'range' => $this->getRangeText($rule),
            'multiplier_text' => $this->getMultiplierText($rule->multiplier),
            'description' => $this->getRuleDescription($rule)
        ];
    }

    /**
     * نص نطاق القاعدة
     */
    private function getRangeText($rule)
    {
        $unit = $this->getUnitText($rule->duration_unit);
        
        if (is_null($rule->max_duration)) {
            return $rule->min_duration . "+ {$unit}";
        }
        
        if ($rule->min_duration == $rule->max_duration) {
            return $rule->min_duration . " {$unit}";
        }
        
        return $rule->min_duration . " - " . $rule->max_duration . " {$unit}";
    }

    /**
     * نص الوحدة بالعربية
     */
    private function getUnitText($unit)
    {
        return match($unit) {
            'day' => 'يوم',
            'week' => 'أسبوع',
            'month' => 'شهر',
            'year' => 'سنة',
            default => $unit,
        };
    }

    /**
     * نص المضاعف
     */
    private function getMultiplierText($multiplier)
    {
        if ($multiplier > 1) {
            $increase = round(($multiplier - 1) * 100, 1);
            return "زيادة {$increase}%";
        } elseif ($multiplier < 1) {
            $discount = round((1 - $multiplier) * 100, 1);
            return "خصم {$discount}%";
        } else {
            return "سعر عادي";
        }
    }

    /**
     * وصف القاعدة
     */
    private function getRuleDescription($rule)
    {
        $range = $this->getRangeText($rule);
        $multiplierText = $this->getMultiplierText($rule->multiplier);
        
        return "للمدة {$range}: {$multiplierText}";
    }

    /**
     * إرجاع تسعيرة مجانية
     */
    private function getFreeQuote($adType)
    {
        return [
            'base_price' => 0,
            'applicable_rule' => null,
            'multiplier' => 1.0,
            'duration' => [
                'value' => $adType->duration_days,
                'unit' => 'day'
            ],
            'total' => 0,
            'is_free' => true,
            'price_breakdown' => [
                'base_daily_price' => 0,
                'multiplier' => 1.0,
                'adjusted_daily_price' => 0,
                'total_days' => $adType->duration_days,
                'duration_text' => $this->getDurationText($adType->duration_days, 'day'),
                'total' => 0,
                'savings' => ['amount' => 0, 'type' => 'none', 'text' => 'إعلان مجاني']
            ],
            'rule_info' => null
        ];
    }

    /**
     * حساب تاريخ الانتهاء
     */
    public function calculateEndDate($startDate, int $value, string $unit)
    {
        $start = Carbon::parse($startDate);
        
        return match($unit) {
            'day' => $start->addDays($value),
            'week' => $start->addWeeks($value),
            'month' => $start->addMonths($value),
            'year' => $start->addYears($value),
            default => $start->addDays($value),
        };
    }

    /**
     * الحصول على الشرائح المتاحة لنوع إعلان
     */
    public function getAvailableTiers($adTypeId, $categoryId = null)
    {
        return AdPricingRule::where('active', true)
            ->where(function($query) use ($adTypeId) {
                $query->whereNull('ad_type_id')
                      ->orWhere('ad_type_id', $adTypeId);
            })
            ->where(function($query) use ($categoryId) {
                $query->whereNull('category_id')
                      ->orWhere('category_id', $categoryId);
            })
            ->orderBy('duration_unit')
            ->orderBy('min_duration')
            ->get()
            ->map(function($rule) {
                return [
                    'id' => $rule->id,
                    'name' => $rule->rule_name,
                    'unit' => $rule->duration_unit,
                    'min_duration' => $rule->min_duration,
                    'max_duration' => $rule->max_duration,
                    'multiplier' => $rule->multiplier,
                    'range_text' => $this->getRangeText($rule),
                    'multiplier_text' => $this->getMultiplierText($rule->multiplier)
                ];
            });
    }
}
