<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class fichiers_affichage extends Model
{
    //

    protected $fillable = [
        'AffichageID','MediaID'
    ];
    
    protected $table = 'fichiers_affichages';
    protected $hidden = [];
    
    
    public $timestamps = false;

}