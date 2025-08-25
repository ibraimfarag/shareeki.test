<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $users = User::orderByDesc('id')->get();

            return DataTables::of($users)->addIndexColumn()
            ->addcolumn('email_verified_at', function($row){ $btn = $row->email_verified_at == null ? 'غير مفعل' : 'مفعل'; return $btn; })
            ->addcolumn('action', function($row){ $btn = '<a href="'.route("users.edit", $row->name) . '" class="btn btn-warning">تعديل</a>'; return $btn; })
            ->addcolumn('actionone', function($row){ $btn = '<a href="'.route("users.delete", [$row->name]).'" class="delete btn btn-danger btn-sm">حذف</a>'; return $btn; })
            ->rawColumns(['action','actionone'])
            ->addIndexColumn()
            ->make(true);

        }

        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::create($request->all());
        return redirect('/admin/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        
        //$request->merge(['password' => bcrypt($request->password)]);
        $user->update($request->all());
      
        return redirect('/admin/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->posts()->delete();
        $user->delete();
        return redirect('/admin/users');
    }
}