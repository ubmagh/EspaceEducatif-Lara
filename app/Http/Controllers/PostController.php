<?php

namespace App\Http\Controllers;

use App\post;
use Exception;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //

    public function CreatePoste_ReturnItsID( $classID, $userID, $text,$approuved){

        try {
            $post = post::create([
                'classId' => $classID,
                'userId' => $userID,
                'date'=>'' . date('Y-d-m H:i:s'),
                'Text' => trim(strip_tags($text)),
                'Approuved' => $approuved ? '1':'0',
            ]);
        } catch (Exception $exc) {
            return response()->json(['error' => 'PostErr', 'content' => $exc ], 200, ['Content-Type' => 'application/json']); 
        }

        return $post->id;

    }

}
