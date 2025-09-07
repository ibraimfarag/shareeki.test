<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function personalInfo()
    {
        return view('main.profile.personal_info.edit');
    }

    public function updatePersonalInfo(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $data = $request->only(['name', 'email', 'phone', 'phone_full']);
        $newPhone = $data['phone_full'] ?? null;
        if ($newPhone && strpos($newPhone, '+') === 0) {
            $newPhone = substr($newPhone, 1);
        }
        $oldPhone = $user->phone;
        if ($newPhone && $newPhone !== $oldPhone) {
            $exists = User::where('phone', $newPhone)->where('id', '!=', $user->id)->exists();
            if ($exists) {
                return redirect()->back()->withErrors(['phone_full' => 'رقم الجوال مستخدم بالفعل من مستخدم آخر'])->withInput();
            }
            $code = $request->input('phone_code');
            $cacheCode = \Cache::get('phone_verification_' . $newPhone);

            if (!$code || $code != $cacheCode) {
                return redirect()->back()->withErrors(['phone_code' => 'كود التفعيل غير صحيح أو منتهي الصلاحية'])->withInput();
            }
            \Cache::forget('phone_verification_' . $newPhone);
        }
        // تحديث رقم الجوال في الحقل الصحيح
        if ($newPhone) {
            $data['phone'] = $newPhone;
            // إذا تم تغيير رقم الجوال، حدث تاريخ التحقق
            $data['phone_verified_at'] = now();
        }
        unset($data['phone_full']);
        $user->update($data);
        return redirect()->route('home')->withMessage('تم التعديل بنجاح');
    }

    public function changePassword()
    {
        return view('main.profile.password.edit');
    }

    public function updateTheChangePassword(Request $request)
    {
        $user = User::whereEmail($request->email)->first();

        if ($user == null)
            return redirect()->back()->withMessage('البريد الإلكترونى غير صحيح');

        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with(['message' => 'من فضلك قم بمراجعة كلمة المرور', 'alert' => 'alert-danger']);
        }

        $user->update($request->except('password_confirmation'));

        return redirect()->route('home')->withMessage('تم التعديل بنجاح');
    }

    public function suggestions()
    {
        return view('main.profile.suggestions.edit');
    }

    public function updateSuggestion(Request $request)
    {
        $DOB = Carbon::parse($request->birth_date);
        $now = Carbon::now();
        $testdate = $DOB->diffInYears($now);
        $errors = '';
        if ($testdate < 18) {
            return redirect()->back()->withErrors($errors)->with(['message' => 'يجب ألا يقل العمر عن 18 عام']);
        }

        $user = User::find(auth()->user()->id);
        $user->update($request->all());
        return redirect()->route('home')->withMessage('تم التعديل بنجاح');

    }
}
