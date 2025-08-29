<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Payment;
use App\Services\FeaturedPostService;
use App\Services\RajhiPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class FeaturedPostController extends Controller
{
    protected FeaturedPostService $featuredPostService;
    protected RajhiPaymentService $rajhiService;

    public function __construct(FeaturedPostService $featuredPostService, RajhiPaymentService $rajhiService)
    {
        $this->featuredPostService = $featuredPostService;
        $this->rajhiService = $rajhiService;
    }

    public function feature(Request $request, $pageID)
    {
        // البحث عن الإعلان بواسطة الـ ID
        $post = Post::find($pageID);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على الإعلان'
            ], 404);
        }

        if (!$this->featuredPostService->canFeaturePost()) {
            return response()->json([
                'success' => false,
                'message' => 'عذراً، جميع الأماكن المميزة محجوزة حالياً'
            ]);
        }

        $postId = $post->id; // حفظ معرف الإعلان في متغير منفصل للاستخدام في catch block

        try {
            \Illuminate\Support\Facades\Log::info('Starting feature process for post: ' . $postId);

            // إنشاء عملية دفع جديدة
            $payment = new Payment([
                'user_id' => auth()->id(),
                'amount' => $this->featuredPostService->getFeaturePrice(),
                'gateway' => 'rajhi',
                'currency' => 'SAR',
                'status' => 'pending',
                'gateway_order_id' => (string) Str::uuid(),
            ]);

            // استخدام العلاقة المورفية لربط الدفع بالإعلان
            $payment->payable()->associate($post);
            $payment->save();

            $payment->payable()->associate($post)->save();

            // تحديث حالة الإعلان إلى معلق حتى يتم الدفع
            $post->update(['status' => 'pending_payment']);

            // تجهيز بيانات الدفع
            // تحديث بيانات الدفع الإضافية
            $payment->description = sprintf(
                'تمييز الإعلان: %s لمدة %d أشهر',
                $post->title,
                $this->featuredPostService->getFeatureDuration()
            );
            $payment->return_url = url('/payments/rajhi/success');
            $payment->cancel_url = url('/payments/rajhi/error');
            $payment->save();

            try {
                // إنشاء طلب دفع مع بوابة الراجحي
                $rajhiResponse = $this->rajhiService->createPayment($payment);

                if ($rajhiResponse['success']) {
                    // تحديث معرف العملية من بوابة الدفع
                    $payment->update([
                        'gateway_transaction_id' => $rajhiResponse['transactionId']
                    ]);

                    // توجيه المستخدم إلى صفحة الدفع الداخلية
                    return response()->json([
                        'success' => true,
                        'redirect_url' => route('payments.checkout', $payment->id),
                        'message' => 'جاري تحويلك لصفحة الدفع...',
                        'amount' => $this->featuredPostService->getFeaturePrice(),
                        'duration' => $this->featuredPostService->getFeatureDuration()
                    ]);
                } else {
                    throw new \Exception('فشل في تهيئة عملية الدفع مع البوابة');
                }
            } catch (\Exception $e) {
                Log::error('Payment initiation failed: ' . $e->getMessage(), [
                    'payment_id' => $payment->id,
                    'post_id' => $post->id
                ]);

                // إلغاء عملية الدفع في حالة الفشل
                $payment->update(['status' => 'failed']);
                $post->update(['status' => 'active']); // إعادة الإعلان لحالته الطبيعية

                return response()->json([
                    'success' => false,
                    'message' => 'عذراً، حدث خطأ أثناء تهيئة عملية الدفع. الرجاء المحاولة مرة أخرى.'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Feature post error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'post_id' => $postId,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء معالجة طلبك. الرجاء المحاولة مرة أخرى.',
                'debug_message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function unfeature(Request $request, Post $post)
    {
        $this->featuredPostService->unfeaturePost($post);

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء تمييز الإعلان بنجاح'
        ]);
    }
}
