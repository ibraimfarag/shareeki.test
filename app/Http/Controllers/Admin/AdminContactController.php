<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use App\Models\Mail\SendContactMessage;
use Yajra\DataTables\DataTables;
use App\Upload\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            //$Contacts = Contact::all();
            $Contacts = Contact::OrderBy('id','DESC')->get();

            return DataTables::of($Contacts)->addIndexColumn()
            ->addcolumn('title', function($row){ $btn = $row->title ?? 'غير موجود'; return $btn; })
            ->addcolumn('action', function($row){ $btn = '<button type="button" class="btn btn-primary"><a href="'.route("contacts.show", [$row->id]).'" style="color: #fff;" target="_blank">قراءة محتوى الرسالة</a></button>'; return $btn; })
            ->rawColumns(['action','title'])
            ->addIndexColumn()
            ->make(true);

        }
        return view('admin.contacts.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge(['image' => Upload::uploadImage($request->file, 'contacts' , rand(0,45454841))]);
        Contact::create($request->except('receiver_email','receiver_name','file'));

        // Send Inbox To A Specific Mail
        //Mail::to($request->receiver_email)->send(new SendContactMessage($request->name, $request->receiver_name, $request->message));

        return 'ok';
    }

    public function show($id)
    {
        return view('admin.contacts.show', ['contact' => Contact::findOrFail($id)]);
    }
}
