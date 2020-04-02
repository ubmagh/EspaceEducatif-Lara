<?php

namespace App\Http\Controllers;

use App\affichage;
use App\fichiers_affichage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffichageController extends Controller
{
    //

    public function Getaffiches(string $classID){
        // get all rows 
        $affichages = affichage::orderBy('date','desc')->get();

        foreach($affichages as $affiche){
            $files=[];
            $medias_ids = fichiers_affichage::where('AffichageID',$affiche->id)->get();
            
            if(!empty($medias_ids))
            foreach($medias_ids as $media_id)
            array_push( $files,app('App\Http\Controllers\MediaController')->GetMedia( $media_id->MediaID ));
            
            unset($affiche->classID);
            $affiche->date = substr($affiche->date,0,16);
            $affiche->files=$files;
        }

       return response()->json(["error" => 'none',"data"=>$affichages], 200);
    }

    public function newAffiche(string $titre,$media,$content,$classID){

        $affichage = new affichage();
        $affichage->title = $titre;
        $affichage->date = ''.date('Y-d-m H:i:s');
        $affichage->content = $content;
        $affichage->classID = (int)$classID;

        $files=[];


        if ( $affichage->save() ){

            foreach( $media as $med ){

                $file = app('App\Http\Controllers\MediaController')->GetMedia( $med );

                $fichier_aff= new fichiers_affichage();
                $fichier_aff->AffichageID=$affichage->id;
                $fichier_aff->MediaID=$file->id;
                $fichier_aff->save();

                unset($file->PosterID);
                unset( $file->date );
                unset($file->path);
                unset ($file->PostID);
                array_push($files,$file);
            }
            $return = ['title'=>$titre, 'content'=>$content, 'id'=>$affichage->id, 'files'=>$files, 'date'=> substr( $affichage->date,0,16) ] ;
            return response()->json(["error" => 'none', 'content'=>$return ], 200);/// back infos to push theme in the view todo
        }
        return response()->json(["error" => 'notSaved'], 200);
    }


    public function checkAffiche_media($affID,$mediaID){
        $container = fichiers_affichage::where('AffichageID',$affID)->where('MediaID',$mediaID)->get();
        if(empty($container))
        return false;
        return true;
    }


    public function GetClassID($affID){
        $affichage = affichage::find($affID)->first();
        if(empty($affichage))
            return null;
        return $affichage->classID;
    }

    public function Delete($affID){

        $aff = affichage::where('id',$affID)->first();

        if($aff->delete())
            return response()->json(["error" => 'none'], 200);
        return response()->json(["error" => 'error'], 200);

    }


    public function Quick_Affichages($classID){

        $affichages=[];
        $affichages = DB::select('SELECT * FROM affichages where DATEDIFF(MONTH, affichages.date, GETDATE())<=2 AND classID=? order by date desc ', [$classID]);
        
        if(!empty($affichages))
        foreach($affichages as $affichage){
            $affichage->files=[];
            $affichage->date = substr($affichage->date,0,16);
            $files_ids = fichiers_affichage::where('AffichageID',$affichage->id)->get();
            foreach($files_ids as $file_id){
                array_push($affichage->files, app('App\Http\Controllers\MediaController')->GetMedia( $file_id->MediaID ) );
            }
        }
        
        return response()->json(["error" => 'none','content'=>$affichages], 200);
    }

}