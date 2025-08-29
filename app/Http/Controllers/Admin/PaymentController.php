<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Settings;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['user', 'payable'])
            ->select('payments.*') // تأكد من اختيار كل الأعمدة
            ->latest()
            ->paginate(15);

        // للتحقق من البيانات
        \Log::info('Payments data:', ['total' => $payments->total(), 'data' => $payments->items()]);

        return view('admin.payments.index', compact('payments'));
    }

    public function show($id)
    {
        $payment = Payment::with(['user', 'payable', 'logs'])
            ->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }

    public function download($id)
    {
        $payment = Payment::with(['user', 'payable'])
            ->findOrFail($id);
        $settings = Settings::first();

        $config = [
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => '3',
            'margin_footer' => '3',
            'margin_top' => '10',
            'margin_bottom' => '10',
            'margin_left' => '10',
            'margin_right' => '10',
            'default_font' => 'xbriyaz',
            'default_font_size' => 12,
            'default_font_color' => ['rgb' => '#333333'],
        ];

        $pdf = PDF::loadView('admin.payments.invoice', compact('payment', 'settings'), [], $config);

        return $pdf->download('invoice-' . $payment->transaction_id . '.pdf');
    }
}
