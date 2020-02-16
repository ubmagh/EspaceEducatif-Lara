<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    //

    public function CheckEtud_PostingPer(string $EtudID){

        $permission = DB::select('select posting from permissions where EtudiantID = ?', [$EtudID]);

        if( !empty($permission) && $permission[0]->posting=="1" )
        return true;

        return false;

    }

    public function CommentPer($EtudID){

        $permission = DB::select('select commenting from permissions where EtudiantID = ?', [$EtudID]);

        if( !empty($permission) && $permission[0]->commenting=="1" )
        return true;

        return false;

    }

}
