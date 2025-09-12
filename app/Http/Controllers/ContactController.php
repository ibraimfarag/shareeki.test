<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Verify Google reCAPTCHA v2
        $recaptchaToken = $request->input('g-recaptcha-response');
        if (empty($recaptchaToken)) {
            return Redirect::back()->withErrors(['captcha' => 'التحقق من reCAPTCHA مطلوب'])->withInput();
        }

        $secret = config('services.recaptcha.secret');
        if (empty($secret)) {
            // If secret not configured, fail closed and inform developer via log
            return Redirect::back()->withErrors(['captcha' => 'reCAPTCHA غير مفعلة، تواصل مع الإدارة'])->withInput();
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secret,
            'response' => $recaptchaToken,
            'remoteip' => $request->ip(),
        ]);

        $body = $response->json();
        if (!isset($body['success']) || $body['success'] !== true) {
            return Redirect::back()->withErrors(['captcha' => 'فشل التحقق من reCAPTCHA، يرجى المحاولة لاحقاً'])->withInput();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:10|max:191',
            'mobile' => 'required|numeric',
            'email' => 'required|string|email',
            'body' => 'required'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
            //return response()->json(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'errors' => $validator->messages() , 'alert' => 'alert-danger'], 422);
        }

        Contact::create($request->all());

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


        return Redirect::back();
        //return 'ok';
    }

    public function show()
    {
        return view('main.contact');
    }
}
