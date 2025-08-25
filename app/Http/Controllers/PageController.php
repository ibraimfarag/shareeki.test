<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Settings;
use Illuminate\Http\Request;

class PageController extends Controller
{

    public function show(Page $the_page)
    {
        return view('main.pages.show', compact('the_page'));
    }
    
    public function payment(Request $request , $amount = 0.1)
    {
        $settings = Settings::find(1);
        if($request->con_val != null)
        {
            $amount = ($request->con_val  * $settings->commission_percentage) / 100;
        }
        $basURL = "https://digitalpayments.alrajhibank.com.sa/pg/payment/hosted.htm";
        $headers = array(
            'Content-type: application/json',
        );
        $response_url = "https://shareeki.net/api/success";
        $error_url = "https://shareeki.net/api/error";
        // List numbers 1 to 20
        $pages = range(1,1000000);
    // Shuffle numbers
        shuffle($pages);
    // Get a page
        $page = array_shift($pages);
        $amount = round($amount, 1);
        // dd($amount);
        $obj = array(array(
            "amt" => $amount,
            "action" => "1", // 1 - Purchase , 4 - Pre-Authorization
            "password" => 'kf6CJ@R12@V7f!i',
            "id" => '2iZubJ0EJ9l00Ko',
            "currencyCode" => "682",
            "trackId" => "$page",
            "responseURL" => $response_url,
            "errorURL" => $error_url,
            "langid" => "ar",
        ));
        $order = json_encode($obj);
        $code = $this->encryption($order , '20204458918420204458918420204458');
        $decode = $this->decryption( $code, '20204458918420204458918420204458');
        $tranData = array(array(
            //Mandatory Parameters
            "id" => '2iZubJ0EJ9l00Ko',
            "trandata" => $code,
            "responseURL" => $response_url,
            "errorURL" => $error_url,
            "langid" => "ar",
        ));
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
            return $err;
        } else {
            $result = json_decode($response, true);
            if($result[0]['status'] == '1')
            {
                $payment_id = substr($result[0]['result'],0, 18);
                // $url = 'https://digitalpayments.alrajhibank.com.sa/pg/payment/hosted.htm=' . $payment_id;
                $url = 'https://digitalpayments.alrajhibank.com.sa/pg/paymentpage.htm?PaymentID=' . $payment_id;

                return redirect()->to($url);
            }else{
                return redirect()->to('https://shareeki.net/api/error');
            }
        }
    }
    
    
    public function encryption ($str, $key)
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

   public function decryption ($code, $key)
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
        return  view('payments.success');
        
    }
    public function error()
    {
        return  view('payments.error');
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
