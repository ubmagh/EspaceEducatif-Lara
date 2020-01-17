<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Contact;
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
            DB::insert('insert into contacts (email, name, message, date) values (?, ?, ?, CURRENT_TIMESTAMP)', [$email, $name, $message])
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
}
