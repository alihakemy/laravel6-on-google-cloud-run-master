<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class comments extends Model
{
    protected $fillable = [
        "comment_id", 'comment_text', 'comment_type', 'comment_media', 'comment_post_id'
        , "comment_user_id", "comment_like_count"
    ];


}
