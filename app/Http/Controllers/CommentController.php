<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    //
    public function NumComments(string $postID){
        $num = DB::select('select count(*) as num from comments where PostId = ?', [$postID]);

        if( !empty ($num[0]->num) )
        return $num[0]->num;

        return 0;
    }
    public function LastComment(string $PostID){
        $com = DB::select('select top 1 * from comments where PostId=? order by date desc',[$PostID]);
        $com= (!empty($com[0]))?$com[0]:[]  ;
        if( !empty ($com) )
        return ['idc'=>$com->userId,'date'=>$com->date,'com'=>$com->Text];

        return [];
    }

    public function NewCommento(string $postID,string $userID,string $Comment){
    
       $comm= Comment::create([
            'userId'=>$userID,
            'PostId'=>$postID,
            'date'=>date('Y-d-m H:i:s'),
            'Text'=> filter_var($Comment,FILTER_SANITIZE_STRING)
        ]);

        return $comm;
        
    }

}
