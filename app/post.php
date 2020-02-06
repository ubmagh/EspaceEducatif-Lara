<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'classId', 'userId', 'Text', 'Approuved', 'date'
    ];

}
