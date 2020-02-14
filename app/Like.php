<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
         'userId', 'PostId'
    ];
}
