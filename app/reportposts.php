<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class reportposts extends Model
{
    protected $fillable = [
        "post_id", 'user_id', 'reporter_id'
    ];


}
