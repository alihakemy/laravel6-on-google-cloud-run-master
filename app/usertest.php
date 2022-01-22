<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class usertest extends Model
{

    protected $fillable = [
        'user_id', 'phoneNo','user_name', 'user_email','user_email_verified_at','user_password',"user_about","user_gender"

   ,"user_img","user_pass","user_follower_count","user_following_count","user_token","user_hobbies","user_countery_code","user_lat",
        "user_lag","PostsCount"
    ];


}
