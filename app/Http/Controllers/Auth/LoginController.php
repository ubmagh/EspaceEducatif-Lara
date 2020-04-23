<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
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
    // protected $redirectTo = RouteServiceProvider::HOME;


     
    protected function redirectTo()
    {

        // $tmp = Auth::guard('api')->user();
        // print_r($tmp);
        // exit;

        if(Auth::user()->UserType == 'admin')
          return 'dachboard';
        else{
            Auth::logout();
            return '/';
        }

    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(Request $request){
        if( Auth::check() && (Auth::user()->UserType == 'admin') )
            return redirect('/home');
        return view('loginAdmin.login');
    }

}