<?php

namespace App\Http\Controllers;

use App\classes\SendFcm;
use App\follow_tabel;
use App\follow_tables;
use App\usertest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class follow_control extends Controller
{


    function removefollow(Request $request)
    {
        $notices = DB::table('follow_tables')
            ->where('id', $request->id)
            ->where('follow', $request->follow)
            ->delete();


        DB::table('usertests')
            ->where('user_id', $request->id)
            ->decrement('user_following_count', 1);


        DB::table('usertests')
            ->where('user_id', $request->follow)
            ->decrement('user_follower_count', 1);

        return $notices;

    }

    /**
     * @param Request $request
     * @param id ->user_id
     * @param user_name -> of user who is doing follow
     * @param img->not add in android yet
     *
     * @param follow -> following id this user i will follow him
     *
     * @return follow_tables
     */
    function addfollow(Request $request)
    {


//        $notices = DB::
//
//        insert('insert into follow_tables (id, follow) values (?, ?)',
//            [$request->id ,$request->follow]);
//
//
//        return $notices;


        $follow = follow_tables::updateOrCreate([
            'id' => $request->id,
            'follow' => $request->follow
        ]);






        DB::table('usertests')
            ->where('user_id', $request->id)
            ->increment('user_following_count', 1);


        DB::table('usertests')
            ->where('user_id', $request->follow)
            ->increment('user_follower_count', 1);




      //  $instance = new SendFcm;

     //   $instance->FcmToken($request->followed_token, $request->user_name, " Following you");


//        if ($request->followed_email != null) {
//            $name = $request->user_name;
//
//            Mail::send([], [], function ($message) use ($name, $request) {
//                $message->to($request->followed_email, 'xchat')
//                    ->subject('Follow')
//                    ->setBody("$name is Following You ", 'text/html');
//                $message->from('support@xchat.today', 'Xcompany server');
//            });
//        }


        return $follow;


    }
}
