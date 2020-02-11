<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class media extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'date', 'PostID', 'PosterID', 'type', 'path','originalName','size'
    ];
}
