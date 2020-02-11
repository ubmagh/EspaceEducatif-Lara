<?php

namespace App\Http\Controllers;
use App\media;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    //
    public function CreateMedia(string $PostID,string $PosterID, string $extension,string $orgName, string $PathName,string $size ){
        
        if( strstr($extension,"image") ){
            $type="image";
            rename(storage_path('app\Classes\TMP').'\\'.$PathName ,storage_path('app\Classes\Images\\').$PathName) ;
        } else
        if( strstr($extension,"video") ){
            $type="video";
            rename(storage_path('app\Classes\TMP').'\\'.$PathName ,storage_path('app\Classes\Videos\\').$PathName) ;
        }else
        if( strstr($extension,"audio") ){
            $type="audio";
            $tmp = explode(".",$orgName);
            $ext = array_pop($tmp);

            $tmp=explode(".",$PathName);
            $FileName=$tmp[0];
            rename(storage_path('app\Classes\TMP').'\\'.$PathName ,storage_path('app\Classes\Audios\\').$FileName.".".$ext) ;
            $PathName=$FileName.".".$ext;
        }
        else{
            //// now i'd work on its extension
            $tmp=explode(".",$orgName);
            $ext = array_pop( $tmp );

            switch($ext){

                case "pdf":
                    $type="pdf";
                    rename(storage_path('app\Classes\TMP').'\\'.$PathName ,storage_path('app\Classes\PDF\\').$PathName) ;
                break;

                case "doc":
                    $type="word";
                    rename(storage_path('app\Classes\TMP').'\\'.$PathName ,storage_path('app\Classes\Word\\').$PathName) ;
                break;
                case "rtf":
                case "docx":
                    $type="word";
                    rename(storage_path('app\Classes\TMP').'\\'.$PathName ,storage_path('app\Classes\Word\\').$PathName.".".$ext) ;
                    $PathName= $PathName.".".$ext;
                break;

                /////there is a kind of problems between ppt and pptx; ppt files are uploaded and saved automaticlly as pptx 
                    /// but pptx files are uknown after uploading so we have to get back their extension
                case "ppt":
                    $type="presentation";
                    rename(storage_path('app\Classes\TMP').'\\'.$PathName ,storage_path('app\Classes\Presentations\\').$PathName) ;
                break;

                case "pptx":
                    $type="presentation";
                    rename(storage_path('app\Classes\TMP').'\\'.$PathName ,storage_path('app\Classes\Presentations\\').$PathName.".".$ext) ;
                    $PathName = $PathName .".".$ext;
                break;

                case "xlsx":
                    $type="Excel";
                    $FileName= explode(".",$PathName);
                    rename(storage_path('app\Classes\TMP').'\\'.$PathName ,storage_path('app\Classes\Excels\\').$FileName[0].".".$ext) ;
                    $PathName = $FileName[0].".".$ext;

                break;

                case "rar":
                case "zip":
                    $type="zip";
                    rename(storage_path('app\Classes\TMP').'\\'.$PathName ,storage_path('app\Classes\Zip\\').$PathName) ;
                break;

            }

        }
        

        try {
            $media = media::create([
                'date' => '' . date('Y-d-m H:i:s'),
                'PostID' => $PostID,
                'PosterID' => $PosterID,
                'type' => ''.$type ,
                'path' => $PathName,
                'originalName'=>$orgName,
                'size'=>$size
            ]);
            $FstInsert = true;
        } catch (Exception $exc) {
            return response()->json(['status' => 'DBError', 'content' => $exc . " creating media row"], 200, ['Content-Type' => 'application/json']);
        }
    }

    public function PostMediasGet(string $PostID){
        $medias = DB::select('select * from media where PostID = ?', [$PostID]);
        $toret=[];
        foreach($medias as $media)
        array_push($toret,["id"=>$media->id,"type"=>$media->type,"OrgName"=>$media->originalName,'size'=>$media->size]);
        return $toret;
    }

}
