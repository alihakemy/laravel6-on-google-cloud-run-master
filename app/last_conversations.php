<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class last_conversations extends Model
{


    protected $fillable = [
        "senderId", "receiverId"
    ];
}
