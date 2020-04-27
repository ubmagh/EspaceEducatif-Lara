<?php

namespace App\Http\Controllers;
use App\media;
use Illuminate\Http\Request;
use Exception;
use Facade\FlareClient\Http\Response;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    //
    public function CreateMedia( $PostID,string $PosterID, string $extension,string $orgName, string $PathName,string $size ){
        
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
            return $media->id;
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


    public function GetMedias_PostID(string $mediaID){
        $media = media::find($mediaID);
        if(empty($media))
            return false;
        return $media->PostID;
    }

    public function GetMedia(string $mediaID){
        $media = media::find($mediaID);
        if(empty($media))
            return null;
        unset($media->PosterID);
        unset( $media->date );
        unset($media->path);
        unset ($media->PostID);
        return $media;
    }

    public function DownloadLink(string $MediaID){

        $media = media::find($MediaID);

        $toDownload = public_path().'/Downloads/'.$media->originalName;
        switch($media->type){
            case "image":
                $file = storage_path().'\app\Classes\Images'.'\\'.$media->path;
            break;
            case "pdf":
                $file = storage_path().'\app\Classes\PDF'.'\\'.$media->path;
            break;
            case "presentation":
                $file = storage_path().'\app\Classes\Presentations'.'\\'.$media->path;
            break;
            case "word":
                $file = storage_path().'\app\Classes\Word'.'\\'.$media->path;
            break;
            case "Excel":
                $file = storage_path().'\app\Classes\Excels'.'\\'.$media->path;
            break;
            case "zip":
                $file = storage_path().'\app\Classes\Zip'.'\\'.$media->path;
            break;
            case "video":
                $file = storage_path().'\app\Classes\Videos'.'\\'.$media->path;
            break;
            case "audio":
                $file = storage_path().'\app\Classes\Audios'.'\\'.$media->path;
            break;
        }
        if(file_exists($file)){
        copy($file,$toDownload);
        return response()->download($toDownload)->deleteFileAfterSend();
     
        }
        return response()->json(['status' => 'NotFound',"content"=>"unauthorized"], 200, ['Content-Type' => 'application/json']);
    }

    public function GetStats(){

        $all_Stats = DB::select(" 
        SELECT 
        ( SELECT count(*) FROM media WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=1 ) as '1',
			( SELECT count(*) FROM media WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=2 ) as '2',
			( SELECT count(*) FROM media WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=3 ) as '3',
			( SELECT count(*) FROM media WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=4 ) as '4',
			( SELECT count(*) FROM media WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=5 ) as '5',
			( SELECT count(*) FROM media WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=6 ) as '6',
			( SELECT count(*) FROM media WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=7 ) as '7',
			( SELECT count(*) FROM media WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=8 ) as '8',
			( SELECT count(*) FROM media WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=9 ) as '9',
			( SELECT count(*) FROM media WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=10) as '10',
			( SELECT count(*) FROM media WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=11) as '11',
			( SELECT count(*) FROM media WHERE YEAR(GETDATE())=YEAR(date) and MONTH(date)=12) as '12'
        ");
        $all_Stats = $all_Stats[0];
        return response()->json( ['data'=>$all_Stats] );



    }

}