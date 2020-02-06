<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    //

    protected $fillable = [
        'Fname', 'Lname', 'email', 'Filiere', 'Sex', 'Annee', 'CIN', 'AvatarPath','dateNaissance'
    ];


    public $timestamps = false;
}
