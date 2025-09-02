<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Settings;
use App\Models\CommissionPayment;
use Illuminate\Http\Request;

class PageController extends Controller
{

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
        $response_url = rtrim($baseUrl, '/') . "/api/success?user_ref=" . urlencode($request->secure_ref);
        $error_url = rtrim($baseUrl, '/') . "/api/error?user_ref=" . urlencode($request->secure_ref);
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
        // فك تشفير الآي دي
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
