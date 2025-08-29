<?php

namespace App\Models;

use App\User;
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
        'gateway_transaction_id', // معرف المعاملة من البوابة
        'description',       // وصف عملية الدفع
        'return_url',        // رابط العودة بعد الدفع
        'cancel_url',        // رابط الإلغاء
        'response_payload',
        'paid_at',
        'payable_type',
        'payable_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
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
