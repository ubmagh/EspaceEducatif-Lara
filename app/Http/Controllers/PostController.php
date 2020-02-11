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

    public function GetPosts(string $classID){

        $posts = DB::select('select top 8 * from posts where classId = ? and Approuved=1 order by date desc ', [$classID]);

        $Posts=[];
        $Posters=[];
        $medias=[];

        foreach($posts as $tmp){
        $post = ['PostID'=>$tmp->id,'date'=>$tmp->date,'text'=>$tmp->Text];
        array_push($Posts,$post);
        $user = app('App\Http\Controllers\UserController')->PosterInfos($tmp->userId);
        array_push($Posters,$user);
        $media = app('App\Http\Controllers\MediaController')->PostMediasGet($tmp->id);
        array_push($medias,$media);
        }


        
    
        $toret=['status'=>'succes','Posts'=>$Posts,'Posters'=>$Posters,'medias'=>$medias]; /// filed with for loop 
        return response($toret,200,['Content-Type' => 'application/json']) ;
    }

}
