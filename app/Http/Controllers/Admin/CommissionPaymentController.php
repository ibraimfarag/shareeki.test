<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommissionPayment;
use App\Models\User;
use Illuminate\Http\Request;

class CommissionPaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = CommissionPayment::with('user')
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('admin.commission_payments.index', compact('payments'));
    }

    public function show($id)
    {
        $payment = CommissionPayment::with('user')->findOrFail($id);
        return view('admin.commission_payments.show', compact('payment'));
    }
}
