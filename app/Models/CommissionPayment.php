<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;

class CommissionPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'commission',
        'status',
        'payment_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
