<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class professeur extends Model
{
    //

    protected $fillable = [
        'Fname', 'Lname', 'email', 'Filiere', 'Sex', 'Matiere', 'AvatarPath'
    ];


    public $timestamps = false;
}
