<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdType extends Model
{
    use HasFactory;
        protected $fillable = [
        'name',
        'base_price',
        'is_paid',
        'duration_days',
        'features',
        'is_recurring',
        'description',
    ];

    protected $casts = [
        'base_price'   => 'decimal:2',
        'is_paid'      => 'boolean',
        'is_recurring' => 'boolean',
        'features'     => 'array',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function pricingRules()
    {
        return $this->hasMany(AdPricingRule::class);
    }

}
