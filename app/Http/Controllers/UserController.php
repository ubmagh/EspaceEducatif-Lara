<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Etudiant;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

//use JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\PayloadFactory;
use Tymon\JWTAuth\JWTManager as JWT;

class UserController extends Controller
{
    //



    public function Login(Request $request)
    {
        $credentials = ["email" => $request->json()->get('email'), "password" => $request->json()->get('password')];
        if ($request->json()->get('save'))
            JWTAuth::factory()->setTTL(4320);
        else
            JWTAuth::factory()->setTTL(1440); /// life time in minutes

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['status' => 'CredErr'], 200, ['Content-Type' => 'application/json']);
            }
        } catch (JWTException $e) {
            return response()->json(['status' => 'TokErr'], 200, ['Content-Type' => 'application/json']);
        }

        $CheckActiveUser = DB::table('users')->where('email', $request->json()->get('email'))->value('Activated');

        if ($CheckActiveUser . "" == "0")
            return response()->json(['status' => 'disactivated'], 200, ['Content-Type' => 'application/json']);

        return response()->json(['status' => 'Success', 'token' => $token, 'LogDate' => '' . date('Y-d-m H:i:s')], 200, ['Content-Type' => 'application/json']);
    }




    public function register(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'Fname' => 'required|string|max:20|min:2',
            'Lname' => 'required|string|max:30|min:2',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'Filiere' => 'required|string|max:3',
            'Annee' => 'required|string|max:1',
            'Sex' => 'required|string|max:1',
            'CIN' => 'required|string|max:10|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'ValidationError', 'content' => $validator->errors()], 400, ['Content-Type' => 'application/json']);
        }

        try {
            $user = User::create([
                'email' => trim(strip_tags($request->json()->get('email'))),
                'password' => Hash::make($request->json()->get('password')),
                'UserType' => 'etud',
                'CreatedAt' => '' . date('Y-d-m H:i:s'),
                'Activated' => 'false',
            ]);
            $FstInsert = true;
        } catch (Exception $exc) {
            return response()->json(['status' => 'DBError', 'content' => $exc . ""], 400, ['Content-Type' => 'application/json']);
        }

        try {
            $etud = Etudiant::create([
                'Fname' => trim(strip_tags($request->json()->get('Fname'))),
                'Lname' => trim(strip_tags($request->json()->get('Lname'))),
                'email' => trim(strip_tags($request->json()->get('email'))),
                'Filiere' => trim(strip_tags($request->json()->get('Filiere'))),
                'Sex' => trim(strip_tags($request->json()->get('Sex'))),
                'Annee' => trim(strip_tags($request->json()->get('Annee'))),
                'CIN' => trim(strip_tags($request->json()->get('CIN'))),
            ]);
        } catch (Exception $exc) {
            if ($FstInsert)
                $user->delete();
            return response()->json(['status' => 'DBError', 'content' => $exc . ""], 400, ['Content-Type' => 'application/json']);
        }


        //$token = JWTAuth::fromUser($user);

        return response()->json(['status' => 'ToValidate', 'content' => 'Wait For Account Validation !'], 201, ['Content-Type' => 'application/json']);
    }


    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(compact('user'));
    }
}
