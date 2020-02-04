<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class classe extends Model
{
    //
    protected $fillable = [
        'ClasseName', 'Filiere', 'Annee', 'ProfID','ImagePath'
    ];

    protected $table = 'classes';
    protected $hidden = [];


    public $timestamps = false;
}
