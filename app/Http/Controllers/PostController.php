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
    

}
