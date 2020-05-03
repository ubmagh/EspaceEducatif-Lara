<?php

namespace App\Http\Controllers;

use App\Like;
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
                'Approuved' => $approuved ? '1':null,
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
        $posts = DB::select( "select top 10 * from posts where classId in (select id from classes where Filiere='".$Filiere."' and Annee='".$Annee."' ) and Approuved=1 order by date desc");
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
        $posts = DB::select( "select * from posts where classId in (select id from classes where Filiere='".$Filiere."' and Annee='".$Annee."' ) and Approuved=1 order by date desc offset ".$toSkip." rows fetch next 5 rows only ");
        
        if(empty($posts))
        return ['status'=>'succes','Posts'=>[],'Posters'=>[],'Classes'=>[],'medias'=>[],'LastComms'=>[],'Likes'=>[]];
        

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


    public function Get_Post_ByID($PostID){
        $tmp = post::find($PostID);

        $medias=[];
        $LastComments =[];
        $Liked=false;
        

        $post = ['PostID'=>$tmp->id,'date'=>$tmp->date,'text'=>$tmp->Text,'likes'=> app('App\Http\Controllers\LikeController')->NumLikes($tmp->id),"Comments"=>app('App\Http\Controllers\CommentController')->NumComments($tmp->id) ] ;
        $Posters = app('App\Http\Controllers\UserController')->PosterInfos($tmp->userId);
        $medias = app('App\Http\Controllers\MediaController')->PostMediasGet($tmp->id);
        $toret=['Posts'=>$post,'Posters'=>$Posters,'medias'=>$medias,'LastComms'=>$LastComments,'Likes'=>$Liked]; 
        return $toret;
    }


    public function GetProf_DashPosts(array $Classes__IDS,string $CurrentUserID){

        $iDs_string = implode(',',$Classes__IDS);
        $posts = DB::select( "select top 10 * from posts where classId in ($iDs_string) and  Approuved=1 order by date desc");
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

    public function GetProf_MorePosts(array $Classes__IDS,string $CurrentUserID, string $offset){

        $toSkip =  8 + $offset*4;
        $iDs_string = implode(',',$Classes__IDS);
        $posts = DB::select( "select * from posts where classId in ($iDs_string) and Approuved=1 order by date desc offset $toSkip rows fetch next 4 rows only");
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



    public function Get_innaprouved_Posts($classID){

        $posts = DB::select('select * from posts where classId = '.$classID.' and Approuved is Null order by date desc ');

        $Posts=[];
        $Posters=[];
        $medias=[];
        
        foreach($posts as $tmp){
      
            

        $post = ['PostID'=>$tmp->id,'date'=>$tmp->date,'text'=>$tmp->Text,'likes'=> app('App\Http\Controllers\LikeController')->NumLikes($tmp->id),"Comments"=>app('App\Http\Controllers\CommentController')->NumComments($tmp->id) ] ;
        array_push($Posts,$post);
        $user = app('App\Http\Controllers\UserController')->PosterInfos($tmp->userId);
        array_push($Posters,$user);
        $media = app('App\Http\Controllers\MediaController')->PostMediasGet($tmp->id);
        array_push($medias,$media);

        }
        
    
        $toret=['status'=>'succes','Posts'=>$Posts,'Posters'=>$Posters,'medias'=>$medias]; /// filed with for loop 
        return response($toret,200,['Content-Type' => 'application/json']) ;

    }


    public function approuve_Delete($classID,$postID,$approuve_delete){
        $post = post::where('classId',$classID)->where('id',$postID)->first();
        if(empty($post))
            return response()->json(["status" => 'Notpermitted'], 200);
        
        $post->Approuved = ($approuve_delete ? 1:0);

        $post->save();
        return response()->json(["status" => 'success'], 200);

    }

    public function getFullpost($postID,$currentUserID){
        $post = Post::find($postID);
        $commentors=[];
        $comments=app('App\Http\Controllers\CommentController')->AllComments($post->id);
        foreach($comments as $comment){
            $tmp =  app('App\Http\Controllers\UserController')->PosterInfos($comment->userId);
            array_push($commentors,$tmp);
        }
        $poster= app('App\Http\Controllers\UserController')->PosterInfos($post->userId);
        $liked=app('App\Http\Controllers\LikeController')->IsLike( $currentUserID,$post->id );
        $postdata = ['PostID'=>$post->id,'date'=>$post->date,'text'=>$post->Text,'likes'=> app('App\Http\Controllers\LikeController')->NumLikes($post->id),"Comments"=>app('App\Http\Controllers\CommentController')->NumComments($post->id) ] ;
        $medias= app('App\Http\Controllers\MediaController')->PostMediasGet($post->id);
        $classe =  app('App\Http\Controllers\ClasseController')->GetClasse_Figured($post->classId);

        $Likers = [];
        $likersPer = Like::where('PostId',$post->id)->get();
        foreach( $likersPer as $personne ){
            array_push( $Likers, app('App\Http\Controllers\UserController')->PosterInfos($personne->userId) );
        }


        $toret=['status'=>'succes','Poster'=>$poster,'Post'=>$postdata,'classe'=>$classe,'medias'=>$medias,'comments'=>$comments,'Liked'=>$liked,'commentors'=>$commentors,'Likers'=>$Likers]; /// filed with for loop 
        return response($toret,200,['Content-Type' => 'application/json']) ;
        
    }

}