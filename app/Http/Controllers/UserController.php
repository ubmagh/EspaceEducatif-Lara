<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/// models
use App\User;
use App\Etudiant;
use App\professeur;
use App\Permission;
use App\post;
use App\classe;
use App\PasswordReset;
use Illuminate\Support\Str;
use App\Mail\UserPwdReset;

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

// mail 
use App\Mail\compteActive;
use Illuminate\Support\Facades\Mail;

// use jwt exceptions ici
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    //



    //////// Omar admin functions here 


    public function listeUser()
    { 
        //first strep:: njibo data model
        $users = User::where('UserType', '=', 'etud')->orWhere('UserType', '=', 'prof')->get();
        
        return view('homeAdmin.users')->with('users',$users);
        
    }

  
    
    public function listeUser_edit(Request $request, $id)
    { 
        //editing
        $user =User::findOrFail($id);
        return view ('homeAdmin.users_edit')->with('user',$user);
    }

    public function listeUser_modifier(Request $request, $id)
    { 

        $this->validate($request,
        [
            'email'=>'nullable|email|unique:users',
            'activation'=> Rule::In(['1','0']) ,
            'password'  =>  'nullable|min:6'
        ],
        [
            'email.email'    =>  ' Adresse Email est invalide ! ',
            'email.unique'  =>  "Adresse Email est Déja enregistré !",
            'activation.in' =>  "Choix invalide",
            'password.min'  =>  "Mot de passe doit etre au min 6Caractères"
        ]);

        //editing
        $user =User::find($id);
        if($request->input('email'))    
        $user->email = $request->input('email');

        if($request->input('password'))    
        $user->password = Hash::make( $request->input('password') );

        $user->Activated = $request->input('activation');
        $user->update();
        $url = config('app.REACT_URL'); /// obtenir le lien du plateform Prof/Etudiant
        
        if($user->Activated)
        Mail::to($user->email)->send( new compteActive($url) );
        return redirect('/liste-utilisateur')->with('status','Modification Faite');
    }

    public function listeUser_delete(Request $request, $id){
        $user = User::find($id);
        if(empty($user))
            return redirect(url('/liste-utilisateur'))->with('notfound','x');
        else 
        $user->delete();
        return redirect(url('/liste-utilisateur'))->with('status','bien supprimé');
    }

    public function user_insert(Request $request)
    {

        $this->validate( $request,
        [
            'email' =>  'required|unique:users|email',
            'password'  =>  'required|min:6',
            'type'  =>  Rule::in(['prof','etud']),
            'activation'    =>  Rule::in(['1','0'])
        ],
        [
            'email.required'    =>  " Saisissez l'adresse Email ! ",
            'email.unique'  =>  'adresse  Email est déja enregistré ',
            'email.email'   =>  "adresse  Email saisi est invalide !",
            'password.required'  =>  'Saisissez le mot de passe',
            'password.min'  =>  'Le mot de passe est de 6 caractères au Min',
            'type.in'   =>  "choix invalide !",
            'activation.in' =>  "choix invalide !"
        ]
        );


     $users=new User;
     $users->email=$request->input('email');
     $users->password=Hash::make($request->input('password'));
    $users->Activated=$request->input('activation');
    $users->UserType=$request->input('type');
    $users->CreatedAt=''.date('Y-d-m H:i:s');


     
    $users->save();
     return redirect('/liste-utilisateur')->with('status','Utilisateur Bien Créer');
    }


    /// / /// end of omar functions








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
            return response()->json(['status' => 'TokErr', "message"=>$e->getMessage()], 200, ['Content-Type' => 'application/json']);
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
            'DateNais'=> 'required|date|date_format:Y-m-d',
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
                'dateNaissance' => $request->json()->get('DateNais'),
            ]);
            $permission = Permission::create([
                'EtudiantID'=>$etud->id,
                 'posting'=>true, 
                 'commenting'=>true, 
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
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'],200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'],200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'],200);
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
            $ret =  app('App\Http\Controllers\ClasseController')->GetClassesList_Stud($etud[0]->Filiere . '', $etud[0]->Annee . '');
            foreach($ret as $classe){
                $ClasseProf = professeur::find($classe->id);
                $ClasseProf_User = user::where('email',$ClasseProf->email)->first();
                $classe->ProfID=['name'=>$ClasseProf->Lname.' '.$ClasseProf->Fname,'id'=>$ClasseProf_User->id];
            }
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

        $data->ImagePath="/images/ClassesWalls/".$data->ImagePath;

         return response()->json(['error' => 'none', 'data' => ['profName'=> $Teacher->Lname." ".$Teacher->Fname , "classeData" => $data ] ], 200, ['Content-Type' => 'application/json']);
    }


    public function NewClassPoste(Request $request){

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


        //// validation required ! before creating post into classe
        if($user->UserType=="prof"){

            $prof=professeur::where('email',$user->email)->first();
           if ( ! app('App\Http\Controllers\ClasseController')->checkProfAccess($prof->id,$request->classID) )
           return   response()->json(['status' => 'NonAuth', 'content' => "Acces not permitted"], 200, ['Content-Type' => 'application/json']);
           $postID = app('App\Http\Controllers\PostController')->CreatePoste_ReturnItsID($request->classID,$user->id,$request->pub,true);

        }else{
            
            $student = Etudiant::where('email',$user->email)->first();

            ////Check posting permissions First !
            if( !app('App\Http\Controllers\PermissionController')->CheckEtud_PostingPer($student->id)  )
           return   response()->json(['status' => 'permission', 'content' => "Acces not permitted"], 200, ['Content-Type' => 'application/json']);

            
           
        $postID = app('App\Http\Controllers\PostController')->CreatePoste_ReturnItsID($request->classID,$user->id,$request->pub,false);
        }


        $lngth = $request->lngth;

        $Files = [];

        for($i=0;$i<$lngth;$i++)
        array_push($Files,$request->{"File".$i});
        

        for($i=0;$i<$lngth;$i++){

            /// placer les uploads dans un dossier TMP pour vérifier leurs types
            $path= Storage::disk('TMP')->put('',$Files[$i]);
            $extension = mime_content_type( storage_path('app\Classes\TMP').'\\'.$path );
            $size = $Files[$i]->getSize();
            app('App\Http\Controllers\MediaController')->CreateMedia( $postID, $user->id, $extension, $Files[$i]->getClientOriginalName(), $path,$size );
        }

        if($user->UserType=="prof"){
            $post = app('App\Http\Controllers\PostController')->Get_Post_ByID($postID);
            return   response()->json(['status' => 'Succes', 'content' => $post], 200, ['Content-Type' => 'application/json']);
        }
        return   response()->json(['status' => 'Succes', 'content' => "succeded"], 200, ['Content-Type' => 'application/json']);
        

    }


    public function Posts(Request $request){
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }

        /// validate access to class posts 
        //// c'est une validation sur les apis non sur les classes allright then ?
        if($user->UserType=="prof"){
            $prof=professeur::where('email',$user->email)->first();
           if ( DB::select("select count(*) as num from classes where id=? and ProfID=?", [$request->classID,$prof->id])[0]->num ==0 )
           return   response()->json(['status' => 'NonAuth', 'content' => "Acces not permitted"], 200, ['Content-Type' => 'application/json']);

        }else{
            $student = Etudiant::where('email',$user->email)->First();
            if ( DB::select("select Filiere+Annee as combi from classes where id=?", [$request->classID])[0]->combi != $student->Filiere.$student->Annee )
           return   response()->json(['status' => 'NonAuth', 'content' => "Acces not permitted"], 200, ['Content-Type' => 'application/json']);
        }

        return app('App\Http\Controllers\PostController')->GetPosts($request->classID,$user->id);

    }


    public function PosterInfos(string $userID){
        $user = User::find($userID);
        $name="";
        $type="";
        if($user->UserType == "prof"){
            $prof = professeur::where('email',$user->email)->first();
            $type="prof";
            $name=$prof->Lname." ".$prof->Fname;
            $pic=$prof->AvatarPath;
        }else{
            $etud = Etudiant::where('email',$user->email)->first();
            $type="etud";
            $name=$etud->Lname." ".$etud->Fname;
            $pic=$etud->AvatarPath;
        }
        $pic="/images/Avatars/".$pic;
        return ['name'=>$name,"type"=>$type,"id"=>$userID,"pic"=>$pic];
    }


    public function NewComment(Request $request){

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }
        $post = post::find($request->postID);
        if($user->UserType==="prof"){
            $prof = professeur::where('email',$user->email)->first();
            if( ! app('App\Http\Controllers\ClasseController')->checkProfAccess($prof->id , $post->classId ))
            return response()->json(['status' => 'NotPermitted', 'content' => "Acces not permitted"], 200, ['Content-Type' => 'application/json']);
        }else{
            $etud = Etudiant::where('email',$user->email)->first();
            if(! app('App\Http\Controllers\ClasseController')->checkEtudiantAccess( $etud->Filiere, $etud->Annee , $post->classId ) )
            return response()->json(['status' => 'NotPermitted', 'content' => "Acces not permitted"], 200, ['Content-Type' => 'application/json']);
            if( !app('App\Http\Controllers\PermissionController')->CommentPer($etud->id)  ){
                return response()->json(['status' => 'Permission', 'content' => "Acces not permitted"], 200, ['Content-Type' => 'application/json']);
            }
        }

        $comment = app('App\Http\Controllers\CommentController')->NewCommento($post->id,$user->id,$request->Comment);

        return  response()->json(['status' => 'successed', 'content' =>  $comment], 200, ['Content-Type' => 'application/json']);
    }



    public function Like(Request $request){

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }

        $post = post::find($request->postID);
        if($user->UserType==="prof"){
            $prof = professeur::where('email',$user->email)->first();
            if( ! app('App\Http\Controllers\ClasseController')->checkProfAccess($prof->id , $post->classId ))
            return response()->json(['status' => 'NotPermitted', 'content' => "Acces not permitted"], 200, ['Content-Type' => 'application/json']);
        }else{
            $etud = Etudiant::where('email',$user->email)->first();
            if(! app('App\Http\Controllers\ClasseController')->checkEtudiantAccess( $etud->Filiere, $etud->Annee , $post->classId ) )
            return response()->json(['status' => 'NotPermitted', 'content' => "Acces not permitted"], 200, ['Content-Type' => 'application/json']);
        }

        if( app('App\Http\Controllers\LikeController')->LikeIt($user->id, $post->id) )
        return  response()->json(['status' => 'successed'], 200, ['Content-Type' => 'application/json']);

        return  response()->json(['status' => 'NotSuccessed'], 200, ['Content-Type' => 'application/json']);

    }


    public function GetClassProf(Request $request){

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }

        $Classe = app('App\Http\Controllers\ClasseController')->GetClasseInfos($request->classID)[0];
        $prof= professeur::find($Classe->ProfID);
        $useri = user::where('email',$prof->email)->first();
        $tab=['id'=>$useri->id,'idProf'=>$prof->id,'name'=>$prof->Lname." ".$prof->Fname,'pic'=>"/images/Avatars/".$prof->AvatarPath];
        $tmp =  app('App\Http\Controllers\ClasseController')->GetClassMates($request->classID);
        $the6=app('App\Http\Controllers\EtudiantController')->Get6ClasseMates($tmp['F'],$tmp['A']);
        return   response()->json(['status' => 'succeded',"content"=>$tab,'the6'=>$the6], 200, ['Content-Type' => 'application/json']);

    }



    public function MyProfileData(Request $requst){

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }

        if( $user->UserType == "prof" ){

            $prof = professeur::where('email',$user->email)->first();
            $created = str_split($user->CreatedAt,10);
            $toret = ['Joined'=>$created[0],'Type'=>'prof',"infos"=>$prof];

        }else{
            $etud = Etudiant::where('email',$user->email)->first();
            $created = str_split($user->CreatedAt,10);
            $toret = ['Joined'=>$created[0],'Type'=>'etud',"infos"=>$etud];
        }
        $toret["infos"]->AvatarPath = "/images/Avatars/".$toret["infos"]->AvatarPath;
        return response()->json(['status' => 'succeded',"content"=>$toret], 200, ['Content-Type' => 'application/json']);
    }

    public function LastLogin(Request $request){
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }

        return response()->json(['status' => 'succeded',"content"=>$user->LastLogin], 200, ['Content-Type' => 'application/json']);

    }

    public function Check_Profile(Request $request){

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }

        $useri = User::find($request->userID);
        
        
        if(!empty($useri)){
            if($user->id==$useri->id)
                return response()->json(['status' => 'succeded',"content"=>["Type"=>"self"]], 200, ['Content-Type' => 'application/json']);
            else 
                if ("admin"==$useri->id)
                    return response()->json(['status' => 'succeded',"content"=>["Type"=>"notFound"]], 200, ['Content-Type' => 'application/json']);
                else{

                    if($useri->UserType =="prof"){
                        $prof= professeur::where('email',$useri->email)->first();
                        $data=[ 'Type'=>"prof", "name"=>$prof->Lname." ".$prof->Fname,'email'=>$prof->email, 'Filiere'=>$prof->Filiere, 'Sex'=>$prof->Sex, 'Matiere'=>$prof->Matiere,'pic'=>'/images/Avatars/'.$prof->AvatarPath ];
                    }else if($useri->UserType =="etud"){
                        $etud= Etudiant::where('email',$useri->email)->first();
                        $data=[ 'Type'=>"etud", "name"=>$etud->Lname." ".$etud->Fname,'email'=>$etud->email, 'Filiere'=>$etud->Filiere, 'Sex'=>$etud->Sex, 'Annee'=>$etud->Annee,'dateNai'=>$etud->dateNaissance ,'pic'=>'/images/Avatars/'.$etud->AvatarPath ];
                    }

                    return response()->json(['status' => 'succeded',"content"=>$data], 200, ['Content-Type' => 'application/json']);
                }
            }
        else
            return response()->json(['status' => 'succeded',"content"=>["Type"=>"notFound"]], 200, ['Content-Type' => 'application/json']);
    }


    public function CheckMedia(Request $request){
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }

        if($user->UserType=="prof"){

            $prof = professeur::where('email',$user->email)->first();
            $postID = app('App\Http\Controllers\MediaController')->GetMedias_PostID($request->MediaID);
            
            if( ! $postID)//// if Media function returns false
            return response()->json(['status' => 'err',"content"=>"notFoundMedia"], 200, ['Content-Type' => 'application/json']);
            $ClassID = app('App\Http\Controllers\PostController')->GetPosts_classeID($postID);
            if ( !app('App\Http\Controllers\ClasseController')->checkProfAccess($prof->id,$ClassID) ){
                return response()->json(['status' => 'Rejected',"content"=>"unauthorized"], 200, ['Content-Type' => 'application/json']);
                }

        }else if($user->UserType=="etud"){

            $etud = Etudiant::where('email',$user->email)->first();

            $postID = app('App\Http\Controllers\MediaController')->GetMedias_PostID($request->MediaID);
            
            if( ! $postID)//// if Media function returns false
            return response()->json(['status' => 'err',"content"=>"notFoundMedia"], 200, ['Content-Type' => 'application/json']);

            $ClassID = app('App\Http\Controllers\PostController')->GetPosts_classeID($postID);
            if ( !app('App\Http\Controllers\ClasseController')->checkEtudiantAccess($etud->Filiere,$etud->Annee,$ClassID) ){
            return response()->json(['status' => 'Rejected',"content"=>"unauthorized"], 200, ['Content-Type' => 'application/json']);
            }
        }
        return response()->json(['status' => 'Exist',"content"=>"succes"], 200, ['Content-Type' => 'application/json']);

    }


    public function GetMedia(Request $request){

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }

        $postID = app('App\Http\Controllers\MediaController')->GetMedias_PostID($request->MediaID);
            
        if( ! $postID)//// if Media function returns false
        return response()->json(['status' => 'err',"content"=>"notFoundMedia"], 200, ['Content-Type' => 'application/json']);
        $ClassID = app('App\Http\Controllers\PostController')->GetPosts_classeID($postID);


        if($user->UserType=="prof"){

            $prof = professeur::where('email',$user->email)->first();
            if ( !app('App\Http\Controllers\ClasseController')->checkProfAccess($prof->id,$ClassID) ){
                return response()->json(['status' => 'Rejected',"content"=>"unauthorized"], 200, ['Content-Type' => 'application/json']);
                }

        }else if($user->UserType=="etud"){

            $etud = Etudiant::where('email',$user->email)->first();

            if ( !app('App\Http\Controllers\ClasseController')->checkEtudiantAccess($etud->Filiere,$etud->Annee,$ClassID) ){
            return response()->json(['status' => 'Rejected',"content"=>"unauthorized"], 200, ['Content-Type' => 'application/json']);
            }
        }
        return app('App\Http\Controllers\MediaController')->DownloadLink($request->MediaID);

    }


    public function DashPosts(Request $request){

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }

        /// validate access to class posts 
        //// c'est une validation sur les apis non sur les classes allright then ?
        if($user->UserType=="prof"){
            $prof=professeur::where('email',$user->email)->first();
            $classes = classe::where('ProfID',$prof->id)->get();
            $classesIDS=[];
            foreach($classes as $classe){
                array_push($classesIDS,$classe->id);
            }
            return app('App\Http\Controllers\PostController')->GetProf_DashPosts($classesIDS,$user->id);   

        }else{
            $student = Etudiant::where('email',$user->email)->First();
            return app('App\Http\Controllers\PostController')->GetStud_DashPosts( $student->Annee, $student->Filiere, $user->id);   
        }
    }
    
    public function DashPosts_MorePosts(Request $request){
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }

        $offset = $request->Offset;

        if(!ctype_digit($offset))
        return response()->json(["error" => 'ParamMissing'], 200);


        //// check users access to this data's



        if($user->UserType=="prof"){
            $prof=professeur::where('email',$user->email)->first();
            $Classes= classe::where('ProfID',$prof->id)->get();
            if(empty($Classes))
            return ['status'=>'succes','Posts'=>[],'Posters'=>[],'Classes'=>[],'medias'=>[],'LastComms'=>[],'Likes'=>[]];
            $Classes__IDS=[];
            foreach($Classes as $classe){
                array_push($Classes__IDS,$classe->id);
            }
            return app('App\Http\Controllers\PostController')->GetProf_MorePosts( $Classes__IDS , $user->id,$offset ); 

        }else{
            $student = Etudiant::where('email',$user->email)->First();
            return app('App\Http\Controllers\PostController')->GetStud_MorePosts( $student->Annee, $student->Filiere, $user->id,$offset);   
        }

    }


    public function Posts_MorePosts(Request $request){

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }

        $offset = $request->Offset;
        $ClassID = $request->classID;

        if(!ctype_digit($offset) || !ctype_digit($ClassID))
        return response()->json(["error" => 'ParamMissing'], 200);

        ## Check on user if he has access to class's posts


        if($user->UserType=="prof"){
            $prof=professeur::where('email',$user->email)->first();

            if( ! app('App\Http\Controllers\ClasseController')->checkProfAccess($prof->id,$ClassID) )
            return response()->json(["error" => 'NotPermitted'], 200);

            return app('App\Http\Controllers\PostController')->Class_More_Posts( $ClassID, $user->id,$offset);   
            

        }else{
            $student = Etudiant::where('email',$user->email)->First();
            if( ! app('App\Http\Controllers\ClasseController')->checkEtudiantAccess($student->Filiere,$student->Annee,$ClassID) )
            return response()->json(["error" => 'NotPermitted'], 200);
            return app('App\Http\Controllers\PostController')->Class_More_Posts( $ClassID, $user->id,$offset);   
        }
    }


    public function ClasseMates(Request $request){

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }
        $ClassID = $request->classID;
        if( !ctype_digit($ClassID))
        return response()->json(["error" => 'ParamMissing'], 200);



        //// user id   +  name + avatar 
        ////                                                       email
        //// classID ---> Filiere  +  Année ---> Etudiants -----------------> users  
        ////                                    name + avatar                 userID 

        ////////   Vérify user Access to ClassMates infos  ?  /// i think its TO-(done)-DO :D
        if($user->UserType=="prof"){
            $prof=professeur::where('email',$user->email)->first();
            if( ! app('App\Http\Controllers\ClasseController')->checkProfAccess($prof->id,$ClassID) )
            return response()->json(["error" => 'Notpermitted'], 200);
        }else{
            $etud=Etudiant::where('email',$user->email)->first();
            if( !app('App\Http\Controllers\ClasseController')->checkEtudiantAccess($etud->Filiere,$etud->Annee,$ClassID) )
            return response()->json(["error" => 'Notpermitted'], 200);
        }


        $ClassInfos = app('App\Http\Controllers\ClasseController')->GetClassMates($ClassID); //// ici j'ai la filiere et l'année de ce classe
        
        $ClassMates_Etudiant = app('App\Http\Controllers\EtudiantController')->Get_Etudiant_ClasseMates( $ClassInfos["F"] , $ClassInfos["A"] );

        $ClassMates = [];
        foreach($ClassMates_Etudiant as $etudiant){

            $user = user::where('email',$etudiant->email)->first();
            $cM = ['id' => $user->id, 'name'=>$etudiant->Fname." ".$etudiant->Lname, 'pic'=>$etudiant->AvatarPath  ];
            array_push($ClassMates,$cM);
        }

        return response()->json(['status' => 'success',"content"=>$ClassMates], 200, ['Content-Type' => 'application/json']);

    }


    public function ClasseCover(Request $request){

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }

        $ClassID = $request->classiD;
        if( !ctype_digit($ClassID))
        return response()->json(["error" => 'ParamMissing'], 200);

        if( $user->UserType=="etud"  )
            return response()->json(["error" => 'Notpermitted'], 200);
        $prof = professeur::where('email',$user->email)->first();
        if( ! app('App\Http\Controllers\ClasseController')->checkProfAccess($prof->id,$ClassID) )
            return response()->json(["error" => 'Notpermitted'], 200);

        $file = $request->newcover;
        
        if(  !is_file($file) )
        return response()->json(["error" => 'ParamMissing'], 200);
        
        $path= Storage::disk('TMP')->put('',$file);

        return app('App\Http\Controllers\ClasseController')->Change_Cover($ClassID,$path);

    }


    public function Affichages(Request $request){

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }

        $ClassID = $request->classiD;
        if( !ctype_digit($ClassID))
        return response()->json(["error" => 'ParamMissing'], 200);


        if($user->UserType=="prof"){
            $prof=professeur::where('email',$user->email)->first();
            if( ! app('App\Http\Controllers\ClasseController')->checkProfAccess($prof->id,$ClassID) )
            return response()->json(["error" => 'Notpermitted'], 200);
        }else{
            $etud=Etudiant::where('email',$user->email)->first();
            if( !app('App\Http\Controllers\ClasseController')->checkEtudiantAccess($etud->Filiere,$etud->Annee,$ClassID) )
            return response()->json(["error" => 'Notpermitted'], 200);
        }
        return app('App\Http\Controllers\AffichageController')->Getaffiches($ClassID);
    }

    public function NewAffichage(Request $request){

         try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }

        $ClassID = $request->classiD;
        if( !ctype_digit($ClassID) || empty($request->titre) || (strlen($request->titre)>60) || ( strlen($request->content)>150 && !empty($request->content) ) || ( $request->lngth!=0 && empty($request->lngth)) )
        return response()->json(["error" => 'ParamMissing'], 200);
        
        if($user->UserType=="etud")
        return response()->json(["error" => 'Notpermitted'], 200);

        $prof=professeur::where('email',$user->email)->first();
        if( ! app('App\Http\Controllers\ClasseController')->checkProfAccess($prof->id,$ClassID) )
        return response()->json(["error" => 'Notpermitted'], 200);
       
        $files= [];
        $lngth = $request->lngth;
        $media= [];

        if($lngth>0){
            for($i=0;$i<$lngth;$i++){
                array_push($files,$request->{"File".$i});
            }
            
            foreach($files as $file){
                $path= Storage::disk('TMP')->put('',$file);
                $extension = mime_content_type( storage_path('app\Classes\TMP').'\\'.$path );
                $size = $file->getSize();
                array_push($media, app('App\Http\Controllers\MediaController')->CreateMedia( null, $user->id, $extension, $file->getClientOriginalName(), $path,$size) );
            }
        }
        $content = trim($request->content);
        if(empty($content))
        $content = null;
        return app('App\Http\Controllers\AffichageController')->newAffiche( $request->titre, $media, $content, $ClassID);
    }

    public function AffichageMedia(Request $request){

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }
        
        $mediaiD = $request->mediaiD;
        $affID = $request->AffiD;
        if( !ctype_digit($mediaiD) || !ctype_digit($affID) )
        return response()->json(["error" => 'ParamMissing'], 200);

        if( !app('App\Http\Controllers\AffichageController')->checkAffiche_media($affID,$mediaiD) )
        return response()->json(["error" => 'ParamMissing'], 200);

        return app('App\Http\Controllers\MediaController')->DownloadLink($mediaiD);
    }


    public function AffichageDel(Request $request){

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }

        $affID = $request->AffiD;
        if( !ctype_digit($affID) )
        return response()->json(["error" => 'ParamMissing'], 200);

        if($user->UserType=="etud")
            return response()->json(["error" => 'Notpermitted'], 200);
        
        $prof=professeur::where('email',$user->email)->first();

        $classID = app('App\Http\Controllers\AffichageController')->GetClassID($affID);

        if(empty($classID))
            return response()->json(["error" => 'NotFound'], 200);


        if( ! app('App\Http\Controllers\ClasseController')->checkProfAccess($prof->id,$classID) )
            return response()->json(["error" => 'Notpermitted'], 200);
        
        return app('App\Http\Controllers\AffichageController')->Delete($affID);
    }

    
    public function QuickAffichages(Request $request){

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }

        $classID = $request->classID;
        if( !ctype_digit($classID) )
            return response()->json(["error" => 'ParamMissing'], 200);

            if($user->UserType=="prof"){
                $prof=professeur::where('email',$user->email)->first();
                if( ! app('App\Http\Controllers\ClasseController')->checkProfAccess($prof->id,$classID) )
                return response()->json(["error" => 'Notpermitted'], 200);
            }else{
                $etud=Etudiant::where('email',$user->email)->first();
                if( !app('App\Http\Controllers\ClasseController')->checkEtudiantAccess($etud->Filiere,$etud->Annee,$classID) )
                return response()->json(["error" => 'Notpermitted'], 200);
            }

            return app('App\Http\Controllers\AffichageController')->Quick_Affichages($classID);

    }



    public function InnaprouvedPosts(Request $request){

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }

        $classID = $request->classID;
        if( !ctype_digit($classID) )
            return response()->json(["status" => 'ParamMissing'], 200);
        
        if($user->UserType!="prof")
            return response()->json(["status" => 'Notpermitted'], 200);

        $prof=professeur::where('email',$user->email)->first();
        if( ! app('App\Http\Controllers\ClasseController')->checkProfAccess($prof->id,$classID) )
            return response()->json(["status" => 'Notpermitted'], 200);

        return app('App\Http\Controllers\PostController')->Get_innaprouved_Posts($classID);  

    }


    public function ApprouvePost(Request $request){
        
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }

        $postID = $request->postID;
        $classID = $request->classID;
        $approuve_delete = $request->approuve ? true:false;

        if( !ctype_digit($postID) || !ctype_digit($classID) )
            return response()->json(["status" => 'ParamMissing'], 200);
        
        if($user->UserType!="prof")
            return response()->json(["status" => 'Notpermitted'], 200);
        $prof=professeur::where('email',$user->email)->first();

        if( ! app('App\Http\Controllers\ClasseController')->checkProfAccess($prof->id,$classID) )
            return response()->json(["status" => 'Notpermitted'], 200);

        return app('App\Http\Controllers\PostController')->approuve_Delete($classID,$postID,$approuve_delete);  
    }


    public function GetSexStats(){
        $all_Stats = DB::select(" Select ( Select count(*) from professeurs Where Sex='M' ) + ( Select count(*) from etudiants Where Sex='M' ) as M , ( Select count(*) from professeurs Where Sex='F' ) + ( Select count(*) from etudiants Where Sex='F' ) as F");
        $all_Stats = $all_Stats[0];
        return response()->json( ['data'=>$all_Stats] );
    }

    public function GetTypesStats(){
        $all_Stats = DB::select("select ( Select count(*) FROM users where UserType='prof' ) as profs , ( Select count(*) FROM users where UserType='etud' ) as etuds");
        $all_Stats = $all_Stats[0];
        return response()->json( ['data'=>$all_Stats] );
    }

    public function adminLastlogin($id){
        $user = User::find($id);
        if( !$user )
            return "";
        $new = strtotime($user->CreatedAt);
        $new = date('Y-d-m H:i:s', $new);
        $user->LastLogin = substr( $new,0,16); 
        $user->CreatedAt = date('Y-d-m H:i:s')."";
        $user->save();
        return $user->CreatedAt."";
    }


    public function ForgotPwd_GetEmail(Request $request){
        $email = $request->email;
        $validation = Validator::make(['email'=>$request->input('email')],
        ['email'=>'email|required']);

        if($validation->fails())
            return response()->json(['status'=>'dataErr'],200);
        
        $user = User::where('email',$email)->first();
        
        if(empty($user))
            return response()->json(['status'=>'notfound'],200);

        $token = Str::random(64);

        $passwordreset = PasswordReset::create([
            'email' =>  $email,
                'token' =>  $token,
                'created_at'    => date('Y-d-m H:i:s').""
        ]);

        $url = config('app.REACT_URL').'/Reset/'.$token."/".urlencode(encrypt($email));
        Mail::to($email)->send( new UserPwdReset($url) );

        return response()->json(['status'=>'sent'],200);
    }


    public function ForgotPwd_Check(Request $request){

        $validation = Validator::make(
            [ 
                'token' =>  $request->input('token'),
                'encmail'   =>   $request->input('encmail'),
            ],
            [
                'token' =>  "required|min:64",
                'encmail'   =>  "required",
            ]
        );

        if($validation->fails())
            return response()->json(['status'=>'err'],200);
            $token =  $request->input('token');
        $email = decrypt( urldecode( $request->input('encmail') ));

        return "hi";
        if( count( PasswordReset::where('email',$email)->where('token',$token)->get() )===0 )
            return response()->json(['status'=>'err'],200);

    }

    public function ForgotPwd_SubmitNewOne(Request $request){

        $validation = Validator::make(
            [ 
                'token' =>  $request->input('token'),
                'encmail'   =>   $request->input('encmail'),
                'pwd'   =>   $request->input('pwd'),
            ],
            [
                'token' =>  "required|min:64",
                'encmail'   =>  "required",
                'pwd'   =>  "required|min:6"
            ]
        );

        if($validation->fails())
            return response()->json(['status'=>'dataErr'],200);

        $token =  $request->input('token');
        $password =  $request->input('pwd');
        $email = decrypt( urldecode( $request->input('encmail') ));
        
        $user = User::where('email',$email)->first();
        if(empty($user))
        return response()->json(['status'=>'notfound'],200);

        if( count( PasswordReset::where('email',$email)->where('token',$token)->get() )===0 )
        return response()->json(['status'=>'notfound'],200);


        $user->password = Hash::make($password);
        $user->update();
        

        DB::delete("delete from password_resets where email='$email' ");

        return response()->json(['status'=>'success'],200);
    }


    public function GetFullPost(Request $request){

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'userNotFound'], 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => 'token_expired'], 200);
        } catch (TokenInvalidException $e) {
            return response()->json(["error" => 'token_invalid'], 200);
        } catch (JWTException $e) {
            return response()->json(["error" => 'token_absent'], 200);
        }

        if( !ctype_digit($request->postID) ){
            return response()->json(["status" => 'dataErr'], 200);
        }
        $postID = $request->postID;
        $post = Post::find($postID);
        if( empty($postID) )
            return response()->json(["status" => 'notFound'], 200);
        


        if( $user->UserType=="prof" ){
            $prof = professeur::where('email',$user->email)->first();
            if( ! app('App\Http\Controllers\ClasseController')->checkProfAccess($prof->id , $post->classId ) )
            return response()->json(["status" => 'unauthorized'], 200);
            
        }else{
            $etud = Etudiant::where('email',$user->email)->first();
            if(!app('App\Http\Controllers\ClasseController')->checkEtudiantAccess($etud->Filiere,$etud->Annee,$post->classId))
                return response()->json(["status" => 'unauthorized'], 200);
        }

        return app('App\Http\Controllers\PostController')->getFullpost($postID,$user->id);

    }


}