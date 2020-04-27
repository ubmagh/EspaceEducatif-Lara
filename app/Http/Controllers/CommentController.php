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


    public function GetStats(){

        $all_Stats = DB::select(" 
        SELECT 
        ( SELECT count(*) FROM comments WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=1 ) as '1',
              ( SELECT count(*) FROM comments WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=2 ) as '2',
              ( SELECT count(*) FROM comments WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=3 ) as '3',
              ( SELECT count(*) FROM comments WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=4 ) as '4',
              ( SELECT count(*) FROM comments WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=5 ) as '5',
              ( SELECT count(*) FROM comments WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=6 ) as '6',
              ( SELECT count(*) FROM comments WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=7 ) as '7',
              ( SELECT count(*) FROM comments WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=8 ) as '8',
              ( SELECT count(*) FROM comments WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=9 ) as '9',
              ( SELECT count(*) FROM comments WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=10) as '10',
              ( SELECT count(*) FROM comments WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=11) as '11',
              ( SELECT count(*) FROM comments WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=12) as '12'
          
        ");
        $all_Stats = $all_Stats[0];
        return response()->json( ['data'=>$all_Stats,'current'=>date('m')] );

    }

}