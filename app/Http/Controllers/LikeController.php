<?php

namespace App\Http\Controllers;

use App\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    //
    public function NumLikes(string $postID){

        $num = DB::select('select count(*) as num from likes where PostId = ?', [$postID]);

        if( !empty ($num[0]->num) )
        return $num[0]->num;

        return 0;
    }

    public function IsLike(string $UserID,string $PostID){

        $data = DB::select('select count(*) as num from likes where userId = ? and PostId = ?', [$UserID,$PostID]);

        if( !empty ($data[0]->num) && $data[0]->num=="1" )
        return true;

        return false;
    }

    public function LikeIt(string $UserID,string $PostID){

        $num = DB::select('select count(*) as num from likes where userId = ? and PostId = ? ', [$UserID,$PostID]);
        if($num[0]->num==1){
            DB::delete('delete likes where userId = ? and PostId = ? ', [$UserID,$PostID]);
        }
        else{
            Like::create([
                'userId'=> $UserID,
                'PostId' => $PostID
            ]);
        }
        return true;
    }

}
