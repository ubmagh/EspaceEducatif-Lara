<?php

namespace App\Http\Controllers;

use App\Etudiant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EtudiantController extends Controller
{
    //

    public function Get6ClasseMates(string $Filiere,string $Annee){

        $the6 = DB::select('select top 6 Lname,Fname,users.id,etudiants.AvatarPath from etudiants inner join users on etudiants.email=users.email where Filiere = ? and Annee=? ', [$Filiere,$Annee]);
        return $the6;
    }

    public function Get_Etudiant_ClasseMates(string $Filiere, string $Annee){


        $classeMates = DB::select('select Fname,Lname,email,AvatarPath from etudiants where Filiere = ? and Annee = ?', [$Filiere,$Annee]);
        return $classeMates;

    }

}
