<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Etudiant;
use App\professeur;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

//use JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\PayloadFactory;
use Tymon\JWTAuth\JWTManager as JWT;

// use jwt exceptions ici
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;


class UserController extends Controller
{
    //



    public function Login(Request $request)
    {
        $credentials = ["email" => $request->json()->get('email'), "password" => $request->json()->get('password'), 'UserType' => $request->json()->get('type')];
        if ($request->json()->get('save'))
            JWTAuth::factory()->setTTL(4320);
        else
            JWTAuth::factory()->setTTL(1440); /// life time in minutes

        try {
            // vérifier les credentiels et créer le token
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['status' => 'CredErr'], 200, ['Content-Type' => 'application/json']);
            }
        } catch (JWTException $e) {
            return response()->json(['status' => 'TokErr'], 200, ['Content-Type' => 'application/json']);
        }

        /*
        $CheckUser = DB::table('users')
            ->select('Activated', 'UserType')
            ->where('email', $credentials["email"])
            ->get();
*/
        $CheckUser = DB::table('users')->where('email', $request->json()->get('email'))->value('Activated');

        ///// si le compe est desactivé
        if ($CheckUser . "" == "0")
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

        if ($request->json()->get('Sex') . "" == "M")
            $AvatarPath = "DefM.png";
        else
            $AvatarPath = "DefF.png";

        try {
            $etud = Etudiant::create([
                'Fname' => trim(strip_tags($request->json()->get('Fname'))),
                'Lname' => trim(strip_tags($request->json()->get('Lname'))),
                'email' => trim(strip_tags($request->json()->get('email'))),
                'Filiere' => trim(strip_tags($request->json()->get('Filiere'))),
                'Sex' => trim(strip_tags($request->json()->get('Sex'))),
                'Annee' => trim(strip_tags($request->json()->get('Annee'))),
                'CIN' => trim(strip_tags($request->json()->get('CIN'))),
                'AvatarPath' => $AvatarPath,
            ]);
        } catch (Exception $exc) {
            if ($FstInsert)
                $user->delete();
            return response()->json(['status' => 'DBError', 'content' => $exc . ""], 400, ['Content-Type' => 'application/json']);
        }

        //make default avatar for user

        if ($request->json()->get('Sex') . "" == "M")

            //$token = JWTAuth::fromUser($user);

            return response()->json(['status' => 'ToValidate', 'content' => 'Wait For Account Validation !'], 201, ['Content-Type' => 'application/json']);
    }


    public function getAuthenticatedUser(Request $request)
    {
        $datee = $request->all();
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired']);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid']);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent']);
        }

        DB::update("update users set LastLogin = '" . $datee["LastLogDate"] . "' where id = ? ", [$user->id]);

        if ($user->UserType . "" == "etud") {
            $details = DB::table('etudiants')
                ->select('Fname', 'Lname', 'Filiere', 'Annee', 'AvatarPath')
                ->where('email', $user->email)
                ->get();
        } else {
            $details = DB::table('professeurs')
                ->select('Fname', 'Lname', 'Filiere', 'Matiere', 'AvatarPath')
                ->where('email', $user->email)
                ->get();
        }

        //// get full avatar url
        $details[0]->AvatarPath = "http://localhost:8000/images/Avatars/" . $details[0]->AvatarPath;

        return response()->json(['error' => 'none', 'user' => ['id' => $user->id, 'email' => $user->email, 'LastLogin' => $user->LastLogin, 'UserType' => $user->UserType], "details" => $details[0], 'LastLogDate' => '' . date('Y-d-m H:i:s')]);
    }


    public function GetInitialClasses(Request $request)
    {

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired']);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid']);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent']);
        }


        if ($user->UserType . "" == "etud") {
            $etud = Etudiant::where('email', $user->email)->get();
            if (empty($etud[0])) {
                return response()->json(["error" => 'NotStudent']);
            }
            $ret =  app('App\Http\Controllers\ClasseController')->GetInitialClassesStud($etud[0]->Filiere . '', $etud[0]->Annee . '');
        } else {
            $prof = professeur::where('email', $user->email)->get();
            if (empty($prof[0])) {
                return response()->json(["error" => 'NotProf']);
            }
            $ret =  app('App\Http\Controllers\ClasseController')->GetInitialClassesProf($prof[0]->id);
        }
        return $ret;
    }


    public function GetClassesList(Request $request)
    {

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired']);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid']);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent']);
        }


        if ($user->UserType . "" == "etud") {
            $etud = Etudiant::where('email', $user->email)->get();
            if (empty($etud[0])) {
                return response()->json(["error" => 'NotStudent']);
            }
            $ret =  app('App\Http\Controllers\ClasseController')->GetInitialClassesStud($etud[0]->Filiere . '', $etud[0]->Annee . '');
        } else {
            $prof = professeur::where('email', $user->email)->get();
            if (empty($prof[0])) {
                return response()->json(["error" => 'NotProf']);
            }
            $ret =  app('App\Http\Controllers\ClasseController')->GetClassesList_Prof($prof[0]->id);
        }
        return $ret;
    }
}
