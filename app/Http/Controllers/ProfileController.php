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
        $user->update($request->all());

        return redirect()->route('home')->withMessage('تم التعديل بنجاح');
   }

   public function changePassword()
   {
       return view('main.profile.password.edit');
   }

   public function updateTheChangePassword(Request $request)
   {
        $user = User::whereEmail($request->email)->first();

        if($user == null) return redirect()->back()->withMessage('البريد الإلكترونى غير صحيح');

        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if($validator->fails()){
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
        $DOB      = Carbon::parse($request->birth_date);
        $now      = Carbon::now();
        $testdate = $DOB->diffInYears($now);
        $errors = '';
        if($testdate < 18)
        {
          return redirect()->back()->withErrors($errors)->with(['message' => 'يجب ألا يقل العمر عن 18 عام']);
        }

        $user = User::find(auth()->user()->id);
        $user->update($request->all());
        return redirect()->route('home')->withMessage('تم التعديل بنجاح');

   }
}
