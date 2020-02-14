<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    //

    public function CheckEtud_Permitted(string $EtudID){

        $permission = DB::select('select posting from permissions where EtudiantID = ?', [$EtudID]);

        if( !empty($permission) && $permission[0]->posting=="1" )
        return true;

        return false;

    }

}
