<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SmsService;
use Illuminate\Support\Facades\Cache;

class PhoneVerificationController extends Controller
{
    public function sendCode(Request $request)
    {

        try {
            $phone = ltrim($request->phone, '+');
            // دمج الرقم المعدل في الريكوست للفاليديشن
            $request->merge(['phone' => $phone]);
            $request->validate([
                'phone' => ['required', 'regex:/^[0-9]{9,15}$/', 'unique:users,phone'],
            ]);
            $code = rand(1000, 9999);
            Cache::put('phone_verification_' . $phone, $code, now()->addMinutes(10));
            $sms = new SmsService();
            // $sms->send($phone, 'كود التفعيل الخاص بك هو: ' . $code);
            \Log::info('تم إرسال كود التفعيل بنجاح', ['phone' => $phone, 'code' => $code]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('خطأ أثناء إرسال كود التفعيل: ' . $e->getMessage(), [
                'phone' => $request->phone,
                'trace' => $e->getTraceAsString(),
            ]);
            $message = $e->getMessage();
            // إذا كان الخطأ من الفاليديشن
            if (method_exists($e, 'errors')) {
                $errors = $e->errors();
                $message = is_array($errors) ? implode('، ', array_map(function ($v) {
                    return is_array($v) ? implode('، ', $v) : $v; }, $errors)) : $message;
            }
            return response()->json(['success' => false, 'message' => $message]);
        }
    }
}
