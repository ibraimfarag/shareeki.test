<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdPricingRule extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'rule_name',
        'category_id',
        'ad_type_id',
        'duration_unit',
        'min_duration',
        'max_duration',
        'multiplier',
        'active',
        'priority',
    ];

    protected $casts = [
        'multiplier' => 'decimal:4',
        'active' => 'boolean',
        'min_duration' => 'integer',
        'max_duration' => 'integer',
        'priority' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function adType()
    {
        return $this->belongsTo(AdType::class);
    }

    // Scope للبحث عن القواعد المطبقة لمدة معينة
    public function scopeForDuration($query, $duration, $unit)
    {
        return $query->where('duration_unit', $unit)
                    ->where('min_duration', '<=', $duration)
                    ->where(function($q) use ($duration) {
                        $q->whereNull('max_duration')
                          ->orWhere('max_duration', '>=', $duration);
                    })
                    ->orderBy('priority', 'desc')
                    ->orderBy('multiplier', 'desc');
    }

    // Helper methods
    public function getDurationRangeTextAttribute()
    {
        $unit = $this->getUnitText();
        
        if (is_null($this->max_duration)) {
            return $this->min_duration . "+ {$unit}";
        }
        
        if ($this->min_duration == $this->max_duration) {
            return $this->min_duration . " {$unit}";
        }
        
        return $this->min_duration . " - " . $this->max_duration . " {$unit}";
    }

    public function getUnitText()
    {
        return match($this->duration_unit) {
            'day' => 'يوم',
            'week' => 'أسبوع',
            'month' => 'شهر',
            'year' => 'سنة',
            default => $this->duration_unit,
        };
    }
}
