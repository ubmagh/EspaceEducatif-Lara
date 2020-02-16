<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EtudiantController extends Controller
{
    //

    public function Get6ClasseMates(string $Filiere,string $Annee){

        $the6 = DB::select('select top 6 Lname,Fname,users.id,etudiants.AvatarPath from etudiants inner join users on etudiants.email=users.email where Filiere = ? and Annee=? ', [$Filiere,$Annee]);
        return $the6;
    }

}
