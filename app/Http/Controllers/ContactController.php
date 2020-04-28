<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Contact;
use App\Etudiant;
use App\professeur;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    //


    public function New(Request $request)
    {

        $validate = Validator::make($request->json()->all(), [
            'email' => 'required|string|email|max:120',
            'name' => 'required|string|min:3|max:20',
            'message' => 'required|string|max:500',
        ]);

        if ($validate->fails())
            return $validate->errors()->toJson();

        $name = $request->json()->get('name');
        $email = $request->json()->get('email');
        $message = $request->json()->get('message');



        if (
            DB::insert('insert into contacts (email, name, message, type, date) values (?, ?, ?, ?, CURRENT_TIMESTAMP)', [$email, $name, $message,"externe"])
        )
            /* //another way to do it but not working in this case
        DB::table('contacts')->insert(
            ['email' => "'" . $email . "'", 'name' => "'" . $name . "'", 'message' => "'" . $message . "'", 'date' => "'CURRENT_TIMESTAMP'"]
        );
        */

            return "true";

        else
            return "false";
    }

    public function Help(string $email,string $name,string $message){
        if(
            DB::insert('insert into contacts (email, name, message, type, date) values (?, ?, ?, ?, CURRENT_TIMESTAMP)', [$email, $name, $message,"interne"])
        )
        return true;
        return false; 
    }


    public function GetStats(){
        $all_Stats = DB::select(" 
        SELECT 
        ( SELECT count(*) FROM contacts WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=1 ) as '1',
			( SELECT count(*) FROM contacts WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=2 ) as '2',
			( SELECT count(*) FROM contacts WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=3 ) as '3',
			( SELECT count(*) FROM contacts WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=4 ) as '4',
			( SELECT count(*) FROM contacts WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=5 ) as '5',
			( SELECT count(*) FROM contacts WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=6 ) as '6',
			( SELECT count(*) FROM contacts WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=7 ) as '7',
			( SELECT count(*) FROM contacts WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=8 ) as '8',
			( SELECT count(*) FROM contacts WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=9 ) as '9',
			( SELECT count(*) FROM contacts WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=10) as '10',
			( SELECT count(*) FROM contacts WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=11) as '11',
			( SELECT count(*) FROM contacts WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=12) as '12'
        ");
        $all_Stats = $all_Stats[0];
        return response()->json( ['data'=>$all_Stats,'current'=>date('m')] );
    }

    public function View(Request $request){
        $messages = Contact::all();
        return view('homeAdmin.MessagesList')->with('messages',$messages);
    }

    public function ViewMessage(Request $request, $id){
        $message = Contact::find($id);
        if( empty($message) )
            return view('errors.404');
        
        if( $message->type=="interne" ){
            $user = User::where('email',$message->email)->first();
            if(!empty($user)){
                if($user->UserType=="prof"){
                    $prof = professeur::where('email',$message->email)->first();
                    $message->name = $prof->Lname.' '.$prof->Fname;
                }else{
                    $etud=  Etudiant::where('email',$message->email)->first();
                    $message->name = $etud->Lname.' '.$etud->Fname;
                }
            }
        }
        
        return view('homeAdmin.Message')->with(['message'=>$message]);
    }

    public function delete(Request $request, $id){
        $message = Contact::find($id);
        if( empty($message) )
            return view('errors.404');
        $message->delete();
        return redirect(url('/Messages'))->with('status','success');
    }

}