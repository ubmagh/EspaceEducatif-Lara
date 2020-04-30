<?php

namespace App\Http\Controllers;

use DB;
use App\classe;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Boolean;
use PhpParser\Node\Expr\Cast\Bool_;

class ClasseController extends Controller
{
    
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

    public function GetClassesList_Stud(string $Filiere, string $Annee){
        $data = classe::where('Filiere', $Filiere)->where('Annee', $Annee)->get();
        foreach($data as $data__)
        unset($data__->ImagePath);
        return $data;
    }

    public function GetClasseInfos(string $ClassID){
        $data = classe::where('id',$ClassID)->get();
        return $data;
    }

    public  function  checkEtudiantAccess(string $Filiere,string $Annee,string $ClassID){

        
        $classe = classe::find($ClassID);
        if(!empty($classe))
        $classe = $classe->get();   

        if( !empty($classe) && $classe[0]->Filiere == $Filiere && $classe[0]->Annee == $Annee )
            return true;

        return false;
    }

    public  function  checkProfAccess(string $ProfID,string $ClassID){

        $classe = classe::where('id',$ClassID)->where('ProfID',$ProfID)->count();

        if( $classe )
            return true;

        return false;
    }

    

    ///Best Part 

    public function GetClassMates(string $classID){
        $classe = classe::find($classID);
        return ['F'=>$classe->Filiere,'A'=>$classe->Annee];
    }


    public function GetClasse_Figured(string $ClassID){
        $classe = classe::find($ClassID);
        return ['id'=>$classe->id,'name'=>$classe->ClasseName,'ids'=>$classe->Filiere.$classe->Annee];
    }


    public function Change_Cover($classID,$path){

        rename(storage_path('app\Classes\TMP').'\\'.$path ,public_path('images\ClassesWalls').'\\'.$path) ;
        $class = classe::find($classID);

        if( $class->ImagePath !="def.jpg" ){
            unlink( public_path('images\ClassesWalls').'\\'.$class->ImagePath );
        }

        $class->ImagePath = $path;
        $class->save();

        return response()->json(["error" => 'none'], 200);

    }


}