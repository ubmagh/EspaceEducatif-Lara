<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'userId', 'PostId','date','Text'
    ];
}
