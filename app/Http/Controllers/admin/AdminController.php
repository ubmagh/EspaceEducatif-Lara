<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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


}