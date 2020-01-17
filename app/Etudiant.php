<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    //

    protected $fillable = [
        'Fname', 'Lname', 'email', 'Filiere', 'Sex', 'Annee', 'CIN',
    ];


    public $timestamps = false;
}
