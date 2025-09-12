<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // التحقق من اللغز الحسابي (مكافحة السبام)
        $sessionAnswer = Session::get('contact_puzzle_answer');
        $userAnswerRaw = trim((string) $request->input('puzzle_answer'));
        // نسمح بإدخال أرقام سالبة في حال عملية طرح (ناتج موجب فقط عندنا لكن احتياط)
        if ($sessionAnswer === null || $userAnswerRaw === '' || !preg_match('/^-?\d+$/', $userAnswerRaw) || (int) $userAnswerRaw !== (int) $sessionAnswer) {
            return Redirect::back()->withErrors(['puzzle' => 'إجابة اللغز (مكافحة السبام) غير صحيحة'])->withInput();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:10|max:191',
            'mobile' => 'required|numeric',
            'email' => 'required|string|email',
            'body' => 'required'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        Contact::create($request->only(['name', 'mobile', 'email', 'body', 'sort']));

        $mailData = $request;
        $support_mail = 'support@shareeki.net';
        $contact_mail = 'relations@shareeki.net';

        if ($request->sort == 'مشكلة') {
            Mail::to($support_mail)->send(new ContactMail($mailData));
            Session::flash('success', 'تم ارسال رسالتك بنجاح');
        } else {
            Mail::to($contact_mail)->send(new ContactMail($mailData));
            Session::flash('success', 'تم ارسال رسالتك بنجاح');
        }


        // مسح جواب اللغز بعد الاستخدام لمنع إعادة الإرسال بنفس القيم
        Session::forget('contact_puzzle_answer');

        return Redirect::back();
    }


    public function show()
    {
        // لغز جمع بسيط فقط (أرقام من 1 إلى 9)
        $op = '+';
        $a = random_int(1, 9);
        $b = random_int(1, 9);
        $expected = $a + $b;
        Session::put('contact_puzzle_answer', $expected);

        return view('main.contact', compact('a', 'b', 'op'));
    }
}
