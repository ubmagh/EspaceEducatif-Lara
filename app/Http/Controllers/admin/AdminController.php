<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\adminPwdReset;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    //

    public function SettingsView(){

        $user = Auth::user();
        return view('homeAdmin.Settings')->with('user',$user);

    }

    public function SettingsSubmit(Request $request){

            $this->validate($request,
            [
                'email' => 'email|unique:users|nullable',
                'pwd'   =>  'min:6|nullable',
                'pwdC'  =>  [Rule::in([$request->input('pwd')])],
                'pwdA'  =>  'required|min:6|password:web'
            ],
            [
                'email.email'   =>  "Email saisi est invalide",
                'email.unique'  =>  " Email Déja enregistré ",
                'pwd.min'  =>  " invalide mot de passe au Min 6 caractères ",
                'pwdC.in'   =>  "Confirmation erronée ",
                'pwdA.required' =>  "L'ancien mot de passe est necessaire pou toute modification",
                'pwdA.min' =>  "mot de passe invalide !",
                'pwdA.password' =>  "mot de passe invalide !"
            ]
            );

            $authUser = Auth::user();
            $user = User::find($authUser->id);
            if(empty($user))
                return redirect()->back()->with('error','true');

            if( $request->input("email") ){
                $user->email = $request->input("email");
                $authUser->email = $request->input("email");
            }
            if($request->input("pwd")){
                $user->password = Hash::make( $request->input("pwd") );
                $authUser->password = Hash::make( $request->input("pwd") );
            }

            $user->save();
            return redirect()->back()->with('error','false');

    }


    public function SendPwdReset(Request $request){

        $this->validate($request,
            [
                'email' => 'email|required',
            ],
            [
                'email.email'   =>  "Email saisi est invalide",
                'email.required'    =>  "Saisissez votre adresse EMail !"
            ]
            );
        $email = $request->input('email');
        $admin = User::where('email',$email)->first();
        if( empty($admin) )
            return redirect()->back()->with('notfound','x');
        
        $token = Str::random(64);

        $passwordreset = PasswordReset::create([
                'email' =>  $email,
                'token' =>  $token,
                'created_at'    => date('Y-d-m H:i:s').""
        ]);
        
        $url = url('/Reset',$token)."?m=".urlencode($email);

    Mail::to($email)->send( new adminPwdReset($url) );
    return redirect()->back()->with(['notfound'=>'o']);
    }



    public function getForm(Request $request,$token){
        if ($token=="")
            return view('loginAdmin.forgot');
        $email =  urldecode( $request->m);
        $validator =  Validator::make(['email'=>$email,'token'=>$token],
        ['email'=>'required|email', 'token'=>'required|min:64|max:64']
        );
        if($validator->fails())
            return view('errors.404');
            
        return view('loginAdmin.resetPWd',['res_Token'=>$token,'res_email'=>$email]);
    }


    public function Reset(Request $request){

        $this->validate($request,
        [
            'res_Token' =>  'required|min:64|max:64',
            'res_email' =>  'required|email',
            'password1'  =>  'required|min:6',
            'password2'  =>  ['required',Rule::in([$request->input('password1')])],
        ],
        [
            'res_Token.min'=>'x',
            'res_Token.required'=>'x',
            'res_Token.max'=>'x',
            'res_email.required'=>'x',
            'res_email.email'=>'x',
            'password1.required'=>'Saisissez votre nouveau Mot de passe.',
            'password1.min'=>'le nouveau mot de passe doit contenir au Min 6 caractères',
            'password2.required'=>'Confirmez votre nouveau Mot de passe.',
            'password2.in'=>' La Confirmation est erronnée .',
        ]
        );

        $password = $request->input('password1');
        $email = $request->input('res_email');
        $res_token = $request->input('res_Token');


        if( count( PasswordReset::where('email',$email)->where('token',$res_token)->get() ) <=0 )
            return view('errors.404');

        $admin = User::where('email',$email)->first();
        if(empty($admin))
        return view('errors.404');

        $admin->password = Hash::make($password);
        $admin->save();
        DB::delete("delete from password_resets where email = '".$email."' ");

        return view('loginAdmin.ResetSuccess');


    }


}