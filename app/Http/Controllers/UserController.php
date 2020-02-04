<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Etudiant;
use App\professeur;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use PhpParser\JsonDecoder;
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
            return response()->json(['status' => 'ValidationError', 'content' => $validator->errors()], 200, ['Content-Type' => 'application/json']);
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
            return response()->json(['status' => 'DBError', 'content' => $exc . ""], 200, ['Content-Type' => 'application/json']);
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
            return response()->json(['status' => 'DBError', 'content' => $exc . ""], 200, ['Content-Type' => 'application/json']);
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


    public function ChangeEmail(Request $request){

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


        //// validate data types
        $validator = Validator::make($request->json()->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'ValidationError', 'content' => $validator->errors()], 200, ['Content-Type' => 'application/json']);
        }


        /// validate password
        if ( ! Hash::check($request->json()->get('password')."", $user->password) ){
            return response()->json(['error' => 'PwDErr']);
        }

        $checkEmailExist = DB::table('users')->select('id')->where('email',$request->json()->get('email')."")->first();
        if(!empty($checkEmailExist))
        if( $checkEmailExist->id."" == $user->id."" ){
            return response()->json(['error' => 'SameEmail'], 200, ['Content-Type' => 'application/json']);
        }else if( strlen($checkEmailExist->id."") !=0 ){
            return response()->json(['error' => 'EmailTaken'], 200, ['Content-Type' => 'application/json']);
        }

        $user->email= $request->json()->get('email');
        $user->save();

        return response()->json(['error' => 'none'], 200, ['Content-Type' => 'application/json']);
    }



    public function ChangePwD(Request $request){


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

        //// validate data types
        $validator = Validator::make($request->json()->all(), [
            'OldPwd' => 'required|string|min:6',
            'NewPwd' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'ValidationError', 'content' => $validator->errors()], 200, ['Content-Type' => 'application/json']);
        }

        /// validate password
        if ( ! Hash::check($request->json()->get('OldPwd')."", $user->password) ){
            return response()->json(['error' => 'PwDErr']);
        }

        $user->password = Hash::make($request->json()->get('NewPwd'));
        $user->save();

        return response()->json(['error' => 'none'], 200, ['Content-Type' => 'application/json']);
    }


    public function ChangeAva(Request $request){

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

        // return $request->file('imgFile')->isValid()?"true":"false"; /// check valide file 

        

        //// validate data types
        $validator = Validator::make($request->all(), [
            'imgFile' => 'required|image|dimensions:max_width=600,max_height=600',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails() || !$request->file('imgFile')->isValid()) {
            return response()->json(['error' => 'ValidationError', 'content' => $validator->errors()], 200, ['Content-Type' => 'application/json']);
        }

         /// validate password
         if ( ! Hash::check($request->input('password')."", $user->password) ){
            return response()->json(['error' => 'PwDErr']);
        }

        if($user->UserType=="prof"){
        $UT = professeur::where('email',$user->email)->first();
        if($UT->AvatarPath != "DefTM.png" && $UT->AvatarPath != "DefTF.png")
        File::delete( public_path()."/images/Avatars/".$UT->AvatarPath );
        }
        else{
        $UT = Etudiant::where('email',$user->email)->first();
        if($UT->AvatarPath != "DefM.png" && $UT->AvatarPath != "DefF.png")
        File::delete( public_path()."/images/Avatars/".$UT->AvatarPath );
        
        }



        $file = $request->file('imgFile');

        $extension=$file->extension(); /// get file extension

        
        $UT->AvatarPath= $user->id.'.'.$extension;
        $UT->save();

        

        Storage::disk('Avatars_upload')->putFileAs('', $file,$user->id.'.'.$extension) ; /// forget about it it is working

        return response()->json(['error' => 'none']);
    }



    public function DefAvatar(Request $request){

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

        $validator = Validator::make($request->json()->all(), [
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'ValidationError', 'content' => $validator->errors()], 200, ['Content-Type' => 'application/json']);
        }

        if ( ! Hash::check($request->json()->get('password')."", $user->password) ){
            return response()->json(['error' => 'PwDErr']);
        }

        if($user->UserType=="prof"){
            $UT = professeur::where('email',$user->email)->first();
            if($UT->AvatarPath != "DefTM.png" && $UT->AvatarPath != "DefTF.png")
                File::delete( public_path()."/images/Avatars/".$UT->AvatarPath );
            if($UT->Sex=="M")
                $UT->AvatarPath= "DefTM.png";
            else
                $UT->AvatarPath="DefTF.png";
            }
            else{
            $UT = Etudiant::where('email',$user->email)->first();
            if($UT->AvatarPath != "DefM.png" && $UT->AvatarPath != "DefF.png")
            File::delete( public_path()."/images/Avatars/".$UT->AvatarPath );
            if($UT->Sex=="M")
                $UT->AvatarPath= "DefM.png";
            else
                $UT->AvatarPath="DefF.png";       
            }

        
        $UT->save();
        return response()->json(['error' => 'none']);
    }


    public function Help(Request $request){

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


        $validator = Validator::make($request->all(), [
            'message' => 'required|string|min:10|max:1200',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'ValidationError', 'content' => $validator->errors()], 200, ['Content-Type' => 'application/json']);
        }
        
        $name = "";
        if($user->UserType."" == "prof"){
            $tmp= professeur::where('email',$user->email)->first();
            $name= $tmp->Lname." ".$tmp->Fname;
        }else{
            $tmp= Etudiant::where('email',$user->email)->first();
            $name= $tmp->Lname." ".$tmp->Fname;
        }

        if(app('App\Http\Controllers\ContactController')->Help( $user->email,$name,$request->message ))
        return response()->json(['error' => 'none', 'content' => $validator->errors()], 200, ['Content-Type' => 'application/json']);
        else
        return response()->json(['error' => 'Failed', 'content' => $validator->errors()], 200, ['Content-Type' => 'application/json']);
    }


    public function GetClasseInfos(Request $request){

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

        if(!empty($request->all()['ClasseID']))
        $ClasseID = $request->all()['ClasseID'];


        $validator = Validator::make($request->all(), [
            'ClasseID' => 'required|int|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'ValidationError', 'content' => $validator->errors()], 200, ['Content-Type' => 'application/json']);
        }
        /// check if current user has access to the classe 
        

        if($user->UserType=="prof"){
        $UT = professeur::where('email',$user->email)->first();
        if( ! app('App\Http\Controllers\ClasseController')->checkProfAccess($UT->id , $ClasseID ) )
            return response()->json(['error' => 'Access Forbidden', 'content' => $validator->errors()], 200, ['Content-Type' => 'application/json']); 
        }
        else{
        $UT = Etudiant::where('email',$user->email)->first();
        if( ! app('App\Http\Controllers\ClasseController')->checkEtudiantAccess( $UT->Filiere, $UT->Annee , $ClasseID )  )
        return response()->json(['error' => 'Access Forbidden', 'content' => $validator->errors()], 200, ['Content-Type' => 'application/json']); 
         }


        $data = app('App\Http\Controllers\ClasseController')->GetClasseInfos( $ClasseID )[0];
        $Teacher= professeur::find($data->ProfID)->get()[0];

        $data->ImagePath="http://localhost:8000/images/ClassesWalls/".$data->ImagePath;

         return response()->json(['error' => 'none', 'data' => ['profName'=> $Teacher->Lname." ".$Teacher->Fname , "classeData" => $data ] ], 200, ['Content-Type' => 'application/json']);
    }











}
