<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\RajhiPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $rajhiService;

    public function __construct(RajhiPaymentService $rajhiService)
    {
        $this->rajhiService = $rajhiService;
    }

    public function checkout(Payment $payment)
    {
        // التحقق من أن الدفع في حالة معلقة
        if ($payment->status !== 'pending') {
            return redirect()->back()->with('error', 'عذراً، لا يمكن معالجة هذا الطلب');
        }

        return view('payments.checkout', compact('payment'));
    }

    public function process(Request $request)
    {
        try {
            \Log::info('Payment process started', $request->all());

            $request->validate([
                'payment_id' => 'required|exists:payments,id',
                'card_name' => 'required|string|max:255',
                'card_number' => 'required|string',
                'expiry' => 'required|string',
                'cvv' => 'required|string'
            ]);

            $payment = Payment::findOrFail($request->payment_id);

            // تنظيف وتنسيق بيانات البطاقة
            $cardNumber = preg_replace('/\D/', '', $request->card_number);
            $expiry = explode('/', $request->expiry);
            $expiryMonth = isset($expiry[0]) ? trim($expiry[0]) : '';
            $expiryYear = isset($expiry[1]) ? trim($expiry[1]) : '';

            \Log::info('Processing payment with data', [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'card_last_4' => substr($cardNumber, -4)
            ]);

            // إرسال معاملة الدفع إلى بوابة الراجحي
            $result = $this->rajhiService->processLocalPayment([
                'cardNumber' => $cardNumber,
                'expiryMonth' => $expiryMonth,
                'expiryYear' => $expiryYear,
                'cvv' => $request->cvv,
                'cardHolderName' => $request->card_name,
                'amount' => $payment->amount,
                'orderNumber' => $payment->id
            ]);

            \Log::info('Payment gateway response', ['result' => $result]);

            if (isset($result['requires_otp']) && $result['requires_otp']) {
                return response()->json([
                    'requires_otp' => true,
                    'otp_reference' => $result['otp_reference'],
                    'message' => $result['message']
                ]);
            }

            if ($result['success']) {
                // تحديث حالة الدفع
                $payment->update([
                    'status' => 'paid',
                    'gateway_reference' => $result['reference']
                ]);

                // تحديث الإعلان إذا كان مرتبطاً
                if ($payment->payable instanceof \App\Models\Post) {
                    $payment->payable->update(['is_featured' => true]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'تم الدفع بنجاح!',
                    'redirect_url' => route('payments.success')
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'فشلت عملية الدفع، يرجى المحاولة مرة أخرى'
            ]);

        } catch (\Exception $e) {
            \Log::error('Payment Processing Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء معالجة الدفع، يرجى المحاولة مرة أخرى',
                'debug_message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function handleSuccess(Request $request)
    {
        try {
            $trackId = $request->input('trackId');
            $paymentId = $request->input('udf1'); // استرجاع معرف الدفع من البيانات المخصصة

            // البحث عن عملية الدفع
            $payment = Payment::find($paymentId);
            if (!$payment) {
                throw new \Exception('Payment not found');
            }

            $result = $this->rajhiService->verifyPayment($trackId, $request->all());

            if ($result) {
                // تحديث حالة الإعلان إلى مميز
                if ($payment->payable instanceof \App\Models\Post) {
                    $payment->payable->update(['is_featured' => true]);
                }

                return redirect()->route('the_posts.show', $payment->payable->id)
                    ->with('success', 'تم تمييز الإعلان بنجاح!');
            }

            return redirect()->route('the_posts.show', $payment->payable->id)
                ->with('error', 'فشلت عملية الدفع');
        } catch (\Exception $e) {
            Log::error('Payment Success Error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            return redirect()->route('my_home')
                ->with('error', 'حدث خطأ أثناء معالجة عملية الدفع');
        }
    }

    public function handleError(Request $request)
    {
        $paymentId = $request->input('udf1');
        $payment = Payment::find($paymentId);

        Log::error('Payment Error', $request->all());

        if ($payment && $payment->payable) {
            return redirect()->route('the_posts.show', $payment->payable->id)
                ->with('error', 'فشلت عملية الدفع');
        }

        return redirect()->route('my_home')
            ->with('error', 'فشلت عملية الدفع');
    }

    public function handleWebhook(Request $request)
    {
        try {
            $this->rajhiService->handleWebhook($request->all());
            return response()->json(['message' => 'Webhook handled successfully']);
        } catch (\Exception $e) {
            Log::error('Webhook Error', [
                'message' => $e->getMessage(),
                'payload' => $request->all()
            ]);
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    // صفحة سجل المدفوعات في لوحة التحكم
    public function index()
    {
        $payments = Payment::with(['payable', 'user'])
            ->latest()
            ->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        return view('admin.payments.show', compact('payment'));
    }

    public function verifyOTP(Request $request)
    {
        try {
            $request->validate([
                'payment_id' => 'required|exists:payments,id',
                'otp' => 'required|string',
                'otp_reference' => 'required|string'
            ]);

            $payment = Payment::findOrFail($request->payment_id);

            $result = $this->rajhiService->verifyOTP([
                'otp' => $request->otp,
                'otp_reference' => $request->otp_reference
            ]);

            if ($result['success']) {
                // تحديث حالة الدفع
                $payment->update([
                    'status' => 'paid',
                    'gateway_reference' => $result['reference']
                ]);

                // تحديث الإعلان إذا كان مرتبطاً
                if ($payment->payable instanceof \App\Models\Post) {
                    $payment->payable->update(['is_featured' => true]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'تم التحقق من الرمز بنجاح!',
                    'redirect_url' => route('payments.success')
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message']
            ]);

        } catch (\Exception $e) {
            \Log::error('OTP Verification Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التحقق من الرمز',
                'debug_message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function handleReturn(Request $request, Payment $payment)
    {
        try {
            $trackId = $request->input('trackid');
            $result = $this->rajhiService->verifyPayment($trackId, $request->all());

            if ($result) {
                // تحديث حالة الدفع
                $payment->update(['status' => 'paid']);

                // تحديث حالة الإعلان المرتبط
                if ($payment->payable instanceof \App\Models\Post) {
                    $payment->payable->update(['is_featured' => true]);
                }

                return redirect()->route('payments.rajhi.success')
                    ->with('success', 'تم الدفع بنجاح!');
            }

            // تحديث حالة الدفع إلى فاشل
            $payment->update(['status' => 'failed']);

            return redirect()->route('payments.rajhi.error')
                ->with('error', 'فشلت عملية الدفع');
        } catch (\Exception $e) {
            Log::error('Return Page Error', [
                'message' => $e->getMessage(),
                'payment_id' => $payment->id,
                'payload' => $request->all()
            ]);

            // تحديث حالة الدفع إلى فاشل في حالة حدوث خطأ
            $payment->update(['status' => 'failed']);

            return redirect()->route('payments.rajhi.error')
                ->with('error', 'حدث خطأ أثناء معالجة الدفع');
        }
    }
}
