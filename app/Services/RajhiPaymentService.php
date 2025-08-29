<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class RajhiPaymentService
{
    protected $config;

    public function __construct()
    {
        $this->config = config('rajhi');
    }

    public function processLocalPayment($data)
    {
        try {
            \Log::info('Starting local payment processing', ['data' => $data]);

            // في بيئة الاختبار، نقوم بمحاكاة عملية الدفع
            if (($this->config['environment'] ?? 'test') === 'test') {
                \Log::info('Using test environment');

                // التحقق من أن البطاقة المستخدمة هي بطاقة اختبار صالحة
                $isTestCard = false;
                foreach ($this->config['test_cards'] as $testCard) {
                    if (str_replace(' ', '', $data['cardNumber']) === $testCard['number']) {
                        $isTestCard = true;
                        break;
                    }
                }

                if ($isTestCard) {
                    // محاكاة طلب OTP لبعض البطاقات في بيئة الاختبار
                    if (Str::endsWith($data['cardNumber'], '1111')) {
                        return [
                            'success' => false,
                            'requires_otp' => true,
                            'otp_reference' => 'TEST_OTP_' . uniqid(),
                            'message' => 'يرجى إدخال رمز التحقق OTP. للاختبار، استخدم: 123456'
                        ];
                    }

                    return [
                        'success' => true,
                        'reference' => 'TEST_' . uniqid(),
                        'message' => 'تمت عملية الدفع بنجاح (بيئة الاختبار)'
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'بطاقة الاختبار غير صالحة. يرجى استخدام إحدى بطاقات الاختبار المتاحة.'
                ];
            }

            // في البيئة الإنتاجية، نقوم بالدفع الفعلي
            $requestParams = [
                'id' => $this->config['terminal_id'],
                'password' => $this->config['tranportal_password'],
                'amt' => number_format($data['amount'], 2, '.', ''),
                'currencyCode' => $this->config['currency'],
                'action' => '1', // 1 للدفع المباشر
                'trackId' => $data['orderNumber'],
                'udf1' => $data['orderNumber'],
                'cardNumber' => $data['cardNumber'],
                'expiryMonth' => $data['expiryMonth'],
                'expiryYear' => $data['expiryYear'],
                'cvv' => $data['cvv'],
                'cardHolderName' => $data['cardHolderName'],
                'tranportalId' => $this->config['tranportal_id'],
                'resourceKey' => $this->config['resource_key']
            ];

            // تسجيل الطلب مع إخفاء البيانات الحساسة
            $logParams = $requestParams;
            unset($logParams['cardNumber'], $logParams['cvv']);

            \Log::info('Sending request to payment gateway', [
                'url' => $this->config['api_url'] . '/direct/pay',
                'params' => $logParams
            ]);

            // إجراء طلب الدفع المباشر
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->config['api_url'] . '/direct/pay', $requestParams);

            $result = $response->json();

            if (isset($result['result']) && $result['result'] === 'CAPTURED') {
                return [
                    'success' => true,
                    'reference' => $result['ref'] ?? null,
                    'message' => 'تمت عملية الدفع بنجاح'
                ];
            }

            return [
                'success' => false,
                'message' => $result['error']['message'] ?? 'فشلت عملية الدفع'
            ];

        } catch (\Exception $e) {
            Log::error('Direct Payment Error', [
                'error' => $e->getMessage(),
                'order_number' => $data['orderNumber']
            ]);

            return [
                'success' => false,
                'message' => 'حدث خطأ أثناء معالجة الدفع'
            ];
        }
    }

    public function createPayment(Payment $payment)
    {
        try {
            \Log::info('Creating Rajhi payment for payment ID: ' . $payment->id);

            $trackId = $payment->gateway_order_id;
            $amount = number_format((float) $payment->amount, 2, '.', '');

            // تجهيز البيانات المطلوبة للدفع
            $requestParams = [
                'id' => $this->config['terminal_id'],
                'password' => $this->config['tranportal_password'],
                'amt' => $amount,
                'currencyCode' => $this->config['currency'],
                'action' => '1',
                'trackId' => $trackId,
                'responseURL' => url($this->config['success_url']),
                'errorURL' => url($this->config['error_url']),
                'langid' => 'AR',
                'udf1' => $payment->id, // لتخزين رقم عملية الدفع
            ];

            // إنشاء التوقيع
            $requestParams['tranportalId'] = $this->config['tranportal_id'];
            $requestParams['responseURL'] = url($this->config['success_url']);
            $requestParams['errorURL'] = url($this->config['error_url']);

            // تحديث رقم التتبع في قاعدة البيانات
            $payment->update([
                'gateway_track_id' => $trackId,
                'gateway_data' => $requestParams
            ]);

            // بناء URL الدفع الكامل
            $paymentUrl = $this->config['payment_url'] . '?' . http_build_query($requestParams);

            return [
                'success' => true,
                'paymentUrl' => $paymentUrl,
                'transactionId' => $trackId
            ];
        } catch (\Exception $e) {
            Log::error('Failed to create Rajhi payment: ' . $e->getMessage(), [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function verifyPayment($trackId, $data)
    {
        // التحقق من صحة المعاملة
        $payment = Payment::where('gateway_track_id', $trackId)->firstOrFail();

        if ($data['result'] === 'CAPTURED') {
            $payment->update([
                'status' => 'completed',
                'gateway_reference' => $data['ref'],
                'gateway_response' => $data
            ]);

            // تحديث حالة الإعلان إلى مميز
            if ($payment->payable_type === 'App\Models\Post') {
                $post = $payment->payable;
                $featuredService = new FeaturedPostService();
                $featuredService->featurePost($post);
            }

            return true;
        }

        $payment->update([
            'status' => 'failed',
            'gateway_response' => $data
        ]);

        return false;
    }

    public function handleWebhook($payload)
    {
        \Log::info('Rajhi Webhook', $payload);

        $trackId = $payload['trackId'] ?? null;
        if (!$trackId) {
            return false;
        }

        return $this->verifyPayment($trackId, $payload);
    }

    public function verifyOTP($data)
    {
        try {
            \Log::info('Verifying OTP', ['reference' => $data['otp_reference']]);

            // في بيئة الاختبار
            if (($this->config['environment'] ?? 'test') === 'test') {
                // للاختبار، نتحقق من أن الرمز هو 123456
                if ($data['otp'] === '123456') {
                    return [
                        'success' => true,
                        'reference' => 'TEST_' . uniqid(),
                        'message' => 'تم التحقق من الرمز بنجاح'
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'رمز التحقق غير صحيح'
                ];
            }

            // في البيئة الإنتاجية
            $requestParams = [
                'id' => $this->config['terminal_id'],
                'password' => $this->config['tranportal_password'],
                'tranportalId' => $this->config['tranportal_id'],
                'otp' => $data['otp'],
                'reference' => $data['otp_reference']
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->config['api_url'] . '/direct/verify-otp', $requestParams);

            $result = $response->json();

            if (isset($result['result']) && $result['result'] === 'SUCCESS') {
                return [
                    'success' => true,
                    'reference' => $result['ref'] ?? null,
                    'message' => 'تم التحقق من الرمز بنجاح'
                ];
            }

            return [
                'success' => false,
                'message' => $result['error']['message'] ?? 'فشل التحقق من الرمز'
            ];

        } catch (\Exception $e) {
            Log::error('OTP Verification Error', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'حدث خطأ أثناء التحقق من الرمز'
            ];
        }
    }
}
