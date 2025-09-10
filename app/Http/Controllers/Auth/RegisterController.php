<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class RegisterController extends Controller
{
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }


    public function showRegistrationForm()
    {
        return view('auth.register');
    }


    public function register(Request $request)
    {
        $data = $request->all();
        if (isset($data['phone_full'])) {
            $data['phone_full'] = ltrim($data['phone_full'], '+');
        }
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_full' => ['required', 'regex:/^(966|971|965|973|968|974)[0-9]{7,12}$/', 'unique:users,phone'],
            'phone_code' => ['required', 'digits:4'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'check_terms' => ['required'],
        ], [
            'phone_code.required' => 'نأمل ادخال الرمز المرسل اليكم🙏🏽',
            'phone_code.digits' => 'يجب أن يكون الرمز مكون من 4 أرقام',
            'name.required' => 'اسم المستخدم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'phone_full.required' => 'رقم الجوال مطلوب',
            'phone_full.regex' => 'رقم الجوال غير صحيح',
            'phone_full.unique' => 'رقم الجوال مستخدم بالفعل',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'check_terms.required' => 'يجب الموافقة على الشروط والأحكام',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $phone = $data['phone_full'];
            \Log::info('بدء التسجيل', ['phone' => $phone, 'data' => $data]);
            $code = Cache::get('phone_verification_' . $phone);
            if (!$code || $data['phone_code'] != $code) {
                \Log::error('كود التفعيل غير صحيح أو منتهي الصلاحية', [
                    'phone' => $phone,
                    'input_code' => $data['phone_code'],
                    'cache_code' => $code,
                ]);
                return redirect()->back()->withErrors(['phone_code' => 'نأمل ادخال الرمز المرسل اليكم🙏🏽 - الرمز غير صحيح أو منتهي الصلاحية'])->withInput();
            }
            \Log::info('كود التفعيل صحيح', ['phone' => $phone, 'code' => $code]);
            Cache::forget('phone_verification_' . $phone);

            $emailVerificationEnabled = env('EMAIL_VERIFICATION_ENABLED', true);

            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $phone,
                'phone_verified_at' => now(),
                'password' => Hash::make($data['password']),
            ];

            if ($emailVerificationEnabled) {
                unset($userData['email_verified_at']);
            } else {
                $userData['email_verified_at'] = now();
            }

            $user = User::create($userData);
            auth()->login($user);

            if ($emailVerificationEnabled) {
                $user->sendEmailVerificationNotification();
                session()->flash('success', 'تم التسجيل بنجاح! يرجى تفعيل البريد الإلكتروني.');
                \Log::info('تم التسجيل بنجاح مع طلب تفعيل البريد', ['user_id' => $user->id, 'phone' => $phone]);
                return redirect()->route('verification.notice');
            } else {
                session()->flash('success', 'تم التسجيل بنجاح!');
                \Log::info('تم التسجيل بنجاح بدون تفعيل البريد', ['user_id' => $user->id, 'phone' => $phone]);
                return redirect($this->redirectTo);
            }
        } catch (\Exception $e) {
            \Log::error('خطأ أثناء التسجيل: ' . $e->getMessage(), [
                'data' => $data,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors(['general' => 'حدث خطأ أثناء التسجيل'])->withInput();
        }
    }
}
