<?php

namespace App\Http\Controllers;

use App\post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    //

    public function CreatePoste_ReturnItsID( $classID, $userID, $text,$approuved){

        try {
            $post = post::create([
                'classId' => $classID,
                'userId' => $userID,
                'date'=>'' . date('Y-d-m H:i:s'),
                'Text' => trim(strip_tags($text)),
                'Approuved' => $approuved ? '1':'0',
            ]);
        } catch (Exception $exc) {
            return response()->json(['error' => 'PostErr', 'content' => $exc ], 200, ['Content-Type' => 'application/json']); 
        }

        return $post->id;
    }

    public function GetPosts(string $classID,string $CurrentUserID){

        $posts = DB::select('select top 8 * from posts where classId = ? and Approuved=1 order by date desc ', [$classID]);

        $Posts=[];
        $Posters=[];
        $medias=[];
        $LastComments =[];
        $Liked=[];
        
        foreach($posts as $tmp){
        $commentor=[];
        $Comment = app('App\Http\Controllers\CommentController')->LastComment($tmp->id);
        if(!empty($Comment)){
        $commentor = app('App\Http\Controllers\UserController')->PosterInfos($Comment['idc']);
        $LastComment=array_merge($Comment,$commentor);
        array_push($LastComments ,$LastComment);}else{
            array_push($LastComments ,[]);
        }

        $post = ['PostID'=>$tmp->id,'date'=>$tmp->date,'text'=>$tmp->Text,'likes'=> app('App\Http\Controllers\LikeController')->NumLikes($tmp->id),"Comments"=>app('App\Http\Controllers\CommentController')->NumComments($tmp->id) ] ;
        array_push($Posts,$post);
        $user = app('App\Http\Controllers\UserController')->PosterInfos($tmp->userId);
        array_push($Posters,$user);
        $media = app('App\Http\Controllers\MediaController')->PostMediasGet($tmp->id);
        array_push($medias,$media);

        array_push($Liked,  app('App\Http\Controllers\LikeController')->IsLike( $CurrentUserID,$tmp->id ) );
        }
        
    
        $toret=['status'=>'succes','Posts'=>$Posts,'Posters'=>$Posters,'medias'=>$medias,'LastComms'=>$LastComments,'Likes'=>$Liked]; /// filed with for loop 
        return response($toret,200,['Content-Type' => 'application/json']) ;
    }

    public function GetPosts_classeID($PostID){
        $classe = post::find($PostID);
        if(empty($classe))
            return false;
        return $classe->classId;
    }
    
    public function GetStud_DashPosts(string $Annee,string $Filiere,string $CurrentUserID){
        $posts = DB::select( "select top 10 * from posts where classId in (select id from classes where Filiere='".$Filiere."' and Annee='".$Annee."' ) order by date desc");
        $Posts=[];
        $Posters=[];
        $medias=[];
        $LastComments =[];
        $Liked=[];
        $Classes=[];

        foreach($posts as $tmp){
        $commentor=[];
        $Comment = app('App\Http\Controllers\CommentController')->LastComment($tmp->id);
        if(!empty($Comment)){
        $commentor = app('App\Http\Controllers\UserController')->PosterInfos($Comment['idc']);
        $LastComment=array_merge($Comment,$commentor);
        array_push($LastComments ,$LastComment);}else{
            array_push($LastComments ,[]);
        }

        $classe= app('App\Http\Controllers\ClasseController')->GetClasse_Figured($tmp->classId);
        array_push($Classes,$classe);
        $post = ['PostID'=>$tmp->id,'date'=>$tmp->date,'text'=>$tmp->Text,'likes'=> app('App\Http\Controllers\LikeController')->NumLikes($tmp->id),"Comments"=>app('App\Http\Controllers\CommentController')->NumComments($tmp->id) ] ;
        array_push($Posts,$post);
        $user = app('App\Http\Controllers\UserController')->PosterInfos($tmp->userId);
        array_push($Posters,$user);
        $media = app('App\Http\Controllers\MediaController')->PostMediasGet($tmp->id);
        array_push($medias,$media);

        array_push($Liked,  app('App\Http\Controllers\LikeController')->IsLike( $CurrentUserID,$tmp->id ) );
        }
        
    
        $toret=['status'=>'succes','Posts'=>$Posts,'Posters'=>$Posters,'Classes'=>$Classes,'medias'=>$medias,'LastComms'=>$LastComments,'Likes'=>$Liked]; /// filed with for loop 
        return response($toret,200,['Content-Type' => 'application/json']) ;


    }



    public function GetStud_MorePosts(string $Annee,string $Filiere,string $CurrentUserID,string $offset){

        $toSkip = 10 + $offset*5 ;
        //// au premier accès au nexwsFeed page 
        //// 10 publication se chargent 
        //// après si l'utilisateur demande plus 
        //// cette fonction charge 5 publication après skipping les 10 + 5*demmandés avant .
        $posts = DB::select( "select * from posts where classId in (select id from classes where Filiere='".$Filiere."' and Annee='".$Annee."' ) order by date desc offset ".$toSkip." rows fetch next 5 rows only ");
        $Posts=[];
        $Posters=[];
        $medias=[];
        $LastComments =[];
        $Liked=[];
        $Classes=[];

        foreach($posts as $tmp){
        $commentor=[];
        $Comment = app('App\Http\Controllers\CommentController')->LastComment($tmp->id);
        if(!empty($Comment)){
        $commentor = app('App\Http\Controllers\UserController')->PosterInfos($Comment['idc']);
        $LastComment=array_merge($Comment,$commentor);
        array_push($LastComments ,$LastComment);}else{
            array_push($LastComments ,[]);
        }

        $classe= app('App\Http\Controllers\ClasseController')->GetClasse_Figured($tmp->classId);
        array_push($Classes,$classe);
        $post = ['PostID'=>$tmp->id,'date'=>$tmp->date,'text'=>$tmp->Text,'likes'=> app('App\Http\Controllers\LikeController')->NumLikes($tmp->id),"Comments"=>app('App\Http\Controllers\CommentController')->NumComments($tmp->id) ] ;
        array_push($Posts,$post);
        $user = app('App\Http\Controllers\UserController')->PosterInfos($tmp->userId);
        array_push($Posters,$user);
        $media = app('App\Http\Controllers\MediaController')->PostMediasGet($tmp->id);
        array_push($medias,$media);

        array_push($Liked,  app('App\Http\Controllers\LikeController')->IsLike( $CurrentUserID,$tmp->id ) );
        }
        
    
        $toret=['status'=>'succes','Posts'=>$Posts,'Posters'=>$Posters,'Classes'=>$Classes,'medias'=>$medias,'LastComms'=>$LastComments,'Likes'=>$Liked]; /// filed with for loop 
        return response($toret,200,['Content-Type' => 'application/json']) ;
    }


    public function Class_More_Posts(string $classID,string $CurrentUserID, string $offset){

        $toSkip =  8 + $offset*4;
        $posts = DB::select('select * from posts where classId = '.$classID.' and Approuved=1 order by date desc offset '.$toSkip.' rows fetch next 4 rows only');

        $Posts=[];
        $Posters=[];
        $medias=[];
        $LastComments =[];
        $Liked=[];
        
        foreach($posts as $tmp){
        $commentor=[];
        $Comment = app('App\Http\Controllers\CommentController')->LastComment($tmp->id);
        if(!empty($Comment)){
        $commentor = app('App\Http\Controllers\UserController')->PosterInfos($Comment['idc']);
        $LastComment=array_merge($Comment,$commentor);
        array_push($LastComments ,$LastComment);}else{
            array_push($LastComments ,[]);
        }

        $post = ['PostID'=>$tmp->id,'date'=>$tmp->date,'text'=>$tmp->Text,'likes'=> app('App\Http\Controllers\LikeController')->NumLikes($tmp->id),"Comments"=>app('App\Http\Controllers\CommentController')->NumComments($tmp->id) ] ;
        array_push($Posts,$post);
        $user = app('App\Http\Controllers\UserController')->PosterInfos($tmp->userId);
        array_push($Posters,$user);
        $media = app('App\Http\Controllers\MediaController')->PostMediasGet($tmp->id);
        array_push($medias,$media);

        array_push($Liked,  app('App\Http\Controllers\LikeController')->IsLike( $CurrentUserID,$tmp->id ) );
        }
        
    
        $toret=['status'=>'succes','Posts'=>$Posts,'Posters'=>$Posters,'medias'=>$medias,'LastComms'=>$LastComments,'Likes'=>$Liked]; /// filed with for loop 
        return response($toret,200,['Content-Type' => 'application/json']) ;

    }

}
