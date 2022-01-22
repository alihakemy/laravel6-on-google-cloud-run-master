<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class posts extends Model
{


    protected $fillable = [
        "id", 'content', 'type', 'video', "img", "post_user_id","like_count"
    ];






}
