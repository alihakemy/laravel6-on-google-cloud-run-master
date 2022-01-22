<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class likesposts extends Model
{

    protected $fillable = [
        "id", 'post_id', 'user_id','liked'
    ];


}
