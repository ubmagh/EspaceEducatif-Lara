<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class affichage extends Model
{
    //
//
protected $fillable = [
    'date', 'title', 'file', 'content','classID'
];

protected $table = 'affichages';
protected $hidden = [];


public $timestamps = false;

}