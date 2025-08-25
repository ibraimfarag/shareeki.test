<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;


        protected $fillable = [
        'user_id',
        'gateway',           // 'rajhi'
        'amount',
        'currency',          // 'SAR'
        'status',            // pending|paid|failed|refunded
        'gateway_order_id',  // UUID من نظامك
        'gateway_ref',       // رقم المعاملة من الراجحي
        'response_payload',
        'paid_at',
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'paid_at'          => 'datetime',
        'response_payload' => 'array',
    ];

    public function payable()
    {
        return $this->morphTo();
    }

    public function logs()
    {
        return $this->hasMany(PaymentLog::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
