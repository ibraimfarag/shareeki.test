<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'admin/dashboard';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin,admin')->except('logout');
    }

    protected function guard()
    {
        //dd('here');
        return auth()->guard('admin');
    }

    public function username()
    {
        return 'email';
    }

    public function showLoginForm()
    {
        $logo = '';
        return view('auth.adminlogin',compact('logo'));
    }

    public function login(Request $request)
    {

        try {
            $admin = Auth::guard('admin')->attempt(["email" => $request->email, "password" => $request->password]);
            if ($admin) {
                return redirect()->route('dashboard');
            }else{
                Session::flash('error','Error Credentials');
                return redirect()->back();
            }
        }catch (\Exception $e){
            dd($e);
        }

    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        return redirect('/admin/login');
    }
}
