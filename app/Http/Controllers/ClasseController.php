<?php

namespace App\Http\Controllers;

use DB;
use App\classe;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    //
    public function GetInitialClassesStud(string $Filiere, string $Annee)
    {
        $data = classe::where('Filiere', $Filiere)->where('Annee', $Annee)->limit(5)->inRandomOrder()->get();
        return $data;
    }

    public function GetInitialClassesProf(string $ProfId)
    {
        $data = classe::where('ProfID', $ProfId)->limit(5)->inRandomOrder()->get();
        return $data;
    }

    public function GetClassesList_Prof(string $ProfId)
    {
        $data = classe::where('ProfID', $ProfId)->OrderBy('ClasseName', 'desc')->get();
        return $data;
    }
}
