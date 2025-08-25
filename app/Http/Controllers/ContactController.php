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
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:10|max:191',
            'mobile' => 'required|numeric',
            'email' => 'required|string|email',
            'body' => 'required'
        ]);

        if($validator->fails()){
            return Redirect::back()->withErrors($validator)->withInput();
            //return response()->json(['message' => 'من فضلك قم بمراجعة تلك الأخطاء', 'errors' => $validator->messages() , 'alert' => 'alert-danger'], 422);
        }

        Contact::create($request->all());

        $mailData = $request;
        $support_mail = 'support@shareeki.net';
        $contact_mail = 'relations@shareeki.net';

        if($request->sort == 'مشكلة'){
            Mail::to($support_mail)->send(new ContactMail($mailData));
            Session::flash('success','تم ارسال رسالتك بنجاح');
        }else{
            Mail::to($contact_mail)->send(new ContactMail($mailData));
            Session::flash('success','تم ارسال رسالتك بنجاح');
        }


        return Redirect::back();
        //return 'ok';
    }

    public function show()
    {
        return view('main.contact');
    }
}
