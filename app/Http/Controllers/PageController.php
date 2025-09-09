<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Settings;
use App\Models\CommissionPayment;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Webhook لاستقبال نتيجة دفع العمولة من بوابة الراجحي
     */
    public function commissionPaymentWebhook(Request $request)
    {
        $trandata = $request->input('trandata');

        if (!$trandata) {
            return response()->json(['error' => 'trandata missing'], 400);
        }

        // فك التشفير
        $decrypted = $this->decryption($trandata, '20204458918420204458918420204458');
        $decoded = json_decode($decrypted, true);

        // التحقق من صحة البيانات المفكوكة
        if (!$decoded || !is_array($decoded)) {
            return response()->json(['error' => 'Invalid trandata format'], 400);
        }

        $data = $decoded[0] ?? [];

        // تسجيل البيانات للتشخيص
        \Log::info('Commission Payment webhook received', ['data' => $data]);

        // تحديث حالة دفع العمولة بناءً على البيانات المستقبلة
        if (isset($data['paymentId'])) {
            $commissionPayment = CommissionPayment::where('payment_id', $data['paymentId'])->first();

            if ($commissionPayment) {
                // تحديد حالة الدفع بناءً على النتيجة
                if (isset($data['result'])) {
                    if ($data['result'] == 'SUCCESS') {
                        $commissionPayment->update(['status' => 'success']);
                        return view('payments.success', ['message' => 'تم دفع العمولة بنجاح!']);

                    } elseif ($data['result'] == 'CANCELED') {
                        $commissionPayment->update(['status' => 'canceled']);
                        return view('payments.error', ['message' => 'تم إلغاء عملية دفع العمولة']);

                    } elseif ($data['result'] == 'NOT CAPTURED') {
                        $commissionPayment->update(['status' => 'failed']);
                        $errorMessage = 'فشل دفع العمولة - الرصيد غير كافي أو البطاقة مرفوضة';
                        if (isset($data['authRespCode']) && $data['authRespCode'] == '57') {
                            $errorMessage = 'فشل دفع العمولة - الرصيد غير كافي في البطاقة';
                        }
                        return view('payments.error', ['message' => $errorMessage]);

                    } else {
                        $commissionPayment->update(['status' => 'failed']);
                        return view('payments.error', ['message' => 'حدث خطأ غير متوقع في دفع العمولة']);
                    }

                } elseif (isset($data['error']) && !empty($data['error'])) {
                    $commissionPayment->update(['status' => 'failed']);

                    $errorMessage = 'فشل دفع العمولة';
                    if (isset($data['errorText'])) {
                        if ($data['error'] == 'IPAY0100207') {
                            $errorMessage = 'نوع البطاقة غير مدعوم لدفع العمولة - يرجى استخدام بطاقة أخرى';
                        } elseif ($data['error'] == 'IPAY0100352') {
                            $errorMessage = 'فشل في التحقق من البطاقة لدفع العمولة - يرجى التأكد من بيانات البطاقة';
                        } else {
                            $errorMessage = $data['errorText'];
                        }
                    }
                    return view('payments.error', ['message' => $errorMessage]);

                } else {
                    // حالة افتراضية للنجاح
                    $commissionPayment->update(['status' => 'success']);
                    return view('payments.success', ['message' => 'تم دفع العمولة بنجاح!']);
                }
            } else {
                return response()->json([
                    'error' => 'Commission payment record not found',
                    'paymentId' => $data['paymentId']
                ], 404);
            }
        } else {
            return response()->json([
                'error' => 'Payment ID missing in commission webhook data',
                'data' => $data
            ], 400);
        }
    }

    /**
     * Webhook لاستقبال نتيجة الدفع من بوابة الراجحي
     */
    public function paymentWebhook(Request $request)
    {
        $trandata = $request->input('trandata');

        if (!$trandata) {
            return response()->json(['error' => 'trandata missing'], 400);
        }

        // فك التشفير
        $decrypted = $this->decryption($trandata, '20204458918420204458918420204458');
        $decoded = json_decode($decrypted, true);

        // التحقق من صحة البيانات المفكوكة
        if (!$decoded || !is_array($decoded)) {
            return response()->json(['error' => 'Invalid trandata format'], 400);
        }

        $data = $decoded[0] ?? [];

        // تسجيل البيانات للتشخيص (يمكن إزالتها لاحقاً)
        \Log::info('Payment webhook received', ['data' => $data]);

        // dd([
        //     'trandata' => $trandata,
        //     'decrypted' => $decrypted,
        //     'decoded' => $decoded,
        //     'data' => $data
        // ]);

        // تحديث حالة الدفع بناءً على البيانات المستقبلة
        if (isset($data['paymentId'])) {
            // dd('Payment ID found', $data['paymentId']);
            $payment = \App\Models\Payment::where('gateway_order_id', $data['paymentId'])->first();
            if ($payment) {
                // dd('Payment record found in database', $payment->toArray());

                // تحديد حالة الدفع بناءً على النتيجة
                if (isset($data['result'])) {
                    // dd('Has result field', $data['result']);
                    // الحالة الأولى: يوجد result (SUCCESS, CANCELED, NOT CAPTURED)

                    if ($data['result'] == 'SUCCESS') {
                        // dd('SUCCESS case - Payment successful', $data);
                        $payment->update(['status' => 'paid']);

                        // تفعيل الإعلان إذا كان الدفع متعلق بإعلان
                        if ($payment->payable_type === 'App\\Models\\Post') {
                            $post = \App\Models\Post::find($payment->payable_id);
                            if ($post) {
                                $post->update([
                                    'is_featured' => true,
                                    'featured_until' => now()->addMonths(3),
                                ]);
                                return redirect()->route('the_posts.show', $post->id)
                                    ->with('success', 'تم تمييز الإعلان بنجاح لمدة 3 أشهر!');
                            }
                        }
                        return view('payments.success', ['message' => 'تم الدفع بنجاح!']);

                    } elseif ($data['result'] == 'CANCELED') {
                        // dd('CANCELED case - Payment canceled', $data,$payment->payable_type);
                        $payment->update(['status' => 'canceled']);

                        return view('payments.error', ['message' => 'تم إلغاء عملية الدفع']);
                    } elseif ($data['result'] == 'NOT CAPTURED') {
                        // dd('NOT CAPTURED case - Payment failed (insufficient funds)', $data);
                        $payment->update(['status' => 'failed']);

                        // رسالة مخصصة للرصيد غير الكافي
                        $errorMessage = 'فشل الدفع - الرصيد غير كافي أو البطاقة مرفوضة';
                        if (isset($data['authRespCode']) && $data['authRespCode'] == '57') {
                            $errorMessage = 'فشل الدفع - الرصيد غير كافي في البطاقة';
                        }


                        return view('payments.error', ['message' => $errorMessage]);

                    } else {
                        // dd('UNKNOWN result case - Unknown payment result', $data);
                        // حالة غير معروفة
                        $payment->update(['status' => 'failed']);
                        return view('payments.error', ['message' => 'حدث خطأ غير متوقع في عملية الدفع']);
                    }

                } elseif (isset($data['error']) && !empty($data['error'])) {
                    // dd('ERROR case - Payment has error', $data);
                    // الحالة الثانية: يوجد error (أخطاء مختلفة)
                    $payment->update(['status' => 'failed']);

                    // تحديد رسالة الخطأ بناءً على نوع الخطأ
                    $errorMessage = 'فشل الدفع';
                    if (isset($data['errorText'])) {
                        if ($data['error'] == 'IPAY0100207') {
                            $errorMessage = 'نوع البطاقة غير مدعوم - يرجى استخدام بطاقة أخرى';
                        } elseif ($data['error'] == 'IPAY0100352') {
                            $errorMessage = 'فشل في التحقق من البطاقة - يرجى التأكد من بيانات البطاقة';
                        } else {
                            $errorMessage = $data['errorText'];
                        }
                    }


                    return view('payments.error', ['message' => $errorMessage]);

                } else {
                    // dd('NO RESULT NO ERROR case - Assuming success', $data);
                    // حالة غير معروفة - افتراض أنها نجاح إذا لم يكن هناك error
                    $payment->update(['status' => 'paid']);

                    if ($payment->payable_type === 'App\\Models\\Post') {
                        $post = \App\Models\Post::find($payment->payable_id);
                        if ($post) {
                            $post->update([
                                'is_featured' => true,
                                'featured_until' => now()->addMonths(3),
                            ]);
                            return redirect()->route('the_posts.show', $post->id)
                                ->with('success', 'تم تمييز الإعلان بنجاح لمدة 3 أشهر!');
                        }
                    }
                    return view('payments.success', ['message' => 'تم الدفع بنجاح!']);
                }
            } else {
                // إذا لم يكن هناك payment
                return response()->json([
                    'error' => 'Payment record not found',
                    'paymentId' => $data['paymentId']
                ], 404);
            }
        } else {
            // إذا لم يكن هناك paymentId
            return response()->json([
                'error' => 'Payment ID missing in webhook data',
                'data' => $data
            ], 400);
        }
    }

    public function show(Page $the_page)
    {

        // dd($the_page);

        if ($the_page->name === 'commission' && !auth()->check()) {
            return redirect()->route('login')
                ->with('message', 'يجب تسجيل الدخول أولاً للوصول لصفحة العمولة');
        }

        // إذا كانت صفحة العمولة وتم تسجيل الدخول
        if ($the_page->name === 'commission') {
            return view('main.pages.commission');
        }



        return view('main.pages.show', compact('the_page'));
    }

    public function payment(Request $request, $amount = 0.1)
    {
        $settings = Settings::find(1);
        // فك تشفير الآي دي والتحقق من وجود المستخدم
        try {
            $userId = decrypt($request->secure_ref);
        } catch (\Exception $e) {
            return response()->json(['error' => 'بيانات المستخدم غير صحيحة'], 400);
        }
        $user = \App\User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'المستخدم غير موجود'], 404);
        }
        $original_amount = $request->con_val;
        if ($original_amount != null) {
            $amount = ($original_amount * $settings->commission_percentage) / 100;
        }
        $basURL = "https://digitalpayments.alrajhibank.com.sa/pg/payment/hosted.htm";
        $headers = array(
            'Content-type: application/json',
        );
        $baseUrl = config('app.url');
        $response_url = $baseUrl . "/api/commission-status";
        $error_url = $baseUrl . "/api/commission-status";
        $pages = range(1, 1000000);
        shuffle($pages);
        $page = array_shift($pages);
        $amount = round($amount, 1);
        $obj = array(
            array(
                "amt" => $amount,
                "action" => "1",
                "password" => 'kf6CJ@R12@V7f!i',
                "id" => '2iZubJ0EJ9l00Ko',
                "currencyCode" => "682",
                "trackId" => "$page",
                "responseURL" => $response_url,
                "errorURL" => $error_url,
                "langid" => "ar",
            )
        );
        $order = json_encode($obj);
        $code = $this->encryption($order, '20204458918420204458918420204458');
        $decode = $this->decryption($code, '20204458918420204458918420204458');
        $tranData = array(
            array(
                "id" => '2iZubJ0EJ9l00Ko',
                "trandata" => $code,
                "responseURL" => $response_url,
                "errorURL" => $error_url,
                "langid" => "ar",
            )
        );
        // سجل العملية في قاعدة البيانات كـ pending
        $commissionPayment = CommissionPayment::create([
            'user_id' => $userId,
            'amount' => $original_amount,
            'commission' => $amount,
            'status' => 'pending',
            'payment_id' => null,
        ]);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $basURL,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($tranData),
            CURLOPT_HTTPHEADER => $headers,
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            // تحديث السجل كفشل
            $commissionPayment->update(['status' => 'failed']);
            return $err;
        } else {
            $result = json_decode($response, true);
            if ($result[0]['status'] == '1') {
                $payment_id = substr($result[0]['result'], 0, 18);
                $commissionPayment->update([
                    'payment_id' => $payment_id,
                    'status' => 'pending',
                ]);
                $url = 'https://digitalpayments.alrajhibank.com.sa/pg/paymentpage.htm?PaymentID=' . $payment_id;
                return redirect()->to($url);
            } else {
                $commissionPayment->update(['status' => 'failed']);
                return redirect()->to(rtrim($baseUrl, '/') . '/api/error');
            }
        }
    }


    public function encryption($str, $key)
    {
        $blocksize = openssl_cipher_iv_length("AES-256-CBC");
        $pad = $blocksize - (strlen($str) % $blocksize);
        $str = $str . str_repeat(chr($pad), $pad);
        $encrypted = openssl_encrypt($str, "AES-256-CBC", $key, OPENSSL_ZERO_PADDING, "PGKEYENCDECIVSPC");
        $encrypted = base64_decode($encrypted);
        $encrypted = unpack('C*', ($encrypted));
        $chars = array_map("chr", $encrypted);
        $bin = join($chars);
        $encrypted = bin2hex($bin);
        $encrypted = urlencode($encrypted);
        return $encrypted;
    }

    public function decryption($code, $key)
    {
        $string = hex2bin(trim($code));
        $code = unpack('C*', $string);
        $chars = array_map("chr", $code);
        $code = join($chars);
        $code = base64_encode($code);
        $decrypted = openssl_decrypt($code, "AES-256-CBC", $key, OPENSSL_ZERO_PADDING, "PGKEYENCDECIVSPC");
        $pad = ord($decrypted[strlen($decrypted) - 1]);
        if ($pad > strlen($decrypted)) {
            return false;
        }
        if (strspn($decrypted, chr($pad), strlen($decrypted) - $pad) != $pad) {
            return false;
        }
        return urldecode(substr($decrypted, 0, -1 * $pad));
    }

    public function success(Request $request)
    {
        $paymentId = $request->input('payment_id');

        // إذا كان هناك payment_id في الطلب، استخدمه
        if ($paymentId) {
            $payment = \App\Models\Payment::find($paymentId);
            if ($payment) {
                $payment->update(['status' => 'paid']);

                // تفعيل الإعلان المميز إذا كان الدفع متعلقًا بإعلان
                if ($payment->payable_type === 'App\\Models\\Post') {
                    $post = \App\Models\Post::find($payment->payable_id);
                    if ($post) {
                        $post->update([
                            'is_featured' => true,
                            'featured_until' => now()->addMonths(3),
                        ]);

                        // عرض صفحة النجاح مع رابط للإعلان
                        return view('payments.success')->with([
                            'message' => 'تم تمييز الإعلان بنجاح لمدة 3 أشهر!',
                            'post_url' => route('the_posts.show', $post->id),
                            'post_title' => $post->title
                        ]);
                    }
                }

                return redirect()->route('payments.success')->with('message', 'تم الدفع بنجاح!');
            }
        }

        // الكود القديم للتعامل مع العمولات
        try {
            $userId = decrypt($request->user_ref);
        } catch (\Exception $e) {
            return view('payments.success')->with('message', 'تم الدفع بنجاح (تعذر تحديد المستخدم)');
        }

        // جلب آخر عملية دفع عمولة للمستخدم وهي معلقة
        $commissionPayment = \App\Models\CommissionPayment::where('user_id', $userId)
            ->where('status', 'pending')
            ->latest()
            ->first();
        if ($commissionPayment) {
            $commissionPayment->update(['status' => 'success']);
        }
        return view('payments.success');
    }

    public function paymentError(Request $request)
    {
        $paymentId = $request->input('payment_id');

        // إذا كان هناك payment_id في الطلب، استخدمه
        if ($paymentId) {
            $payment = \App\Models\Payment::find($paymentId);
            if ($payment) {
                $payment->update(['status' => 'failed']);

                if ($payment->payable_type === 'App\\Models\\Post') {
                    $post = \App\Models\Post::find($payment->payable_id);
                    if ($post) {
                        return redirect()->route('the_posts.show', $post->id)
                            ->with('error', 'فشلت عملية الدفع، يرجى المحاولة مرة أخرى');
                    }
                }
            }
        }

        return redirect()->route('my_home')->with('error', 'فشلت عملية الدفع، يرجى المحاولة مرة أخرى');
    }

    public function error()
    {
        return view('payments.error');
    }

    public function showPromote(Request $request)
    {
        // $this->authorize('update', $post);
        dd();
        // جلب أنواع الإعلانات المدفوعة
        $adTypes = \App\Models\AdType::where('is_paid', true)
            ->orderBy('base_price')
            ->get();

        // تحقق أن هناك أنواع متاحة
        if ($adTypes->isEmpty()) {
            return back()->with('error', 'لا توجد أنواع إعلانات مميزة متاحة حالياً');
        }

        return view('admin.posts.promote', compact('post', 'adTypes'));
    }
}
