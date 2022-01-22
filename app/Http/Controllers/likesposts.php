<?php

namespace App\Http\Controllers;

use App\saveds;
use App\usertest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class likesposts extends Controller
{
    function addlike(Request $request)
    {

       // $this->fcm($request->post_owner_token, $request->user_name, $request->post_content);
        $like =  \App\likesposts::updateOrCreate([
            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
            'liked' => "true",


        ]);




        if ($like->wasRecentlyCreated) {
            DB::table('posts')
                ->where('id', $request->post_id)
                ->increment('like_count', 1);
        }


        $name = $request->user_name;
//        Mail::send([], [], function ($message) use ($name, $request) {
//            $message->to($request->post_owner_email, 'Xchat')
//                ->subject('Like')
//                ->setBody("$name is Like Your Post ", 'text/html');
//            $message->from('support@xchat.today', 'Xcompany server');
//        });



//        saveds::updateOrCreate([
//            'user_id' => $request->user_id,
//            'post_id' => $request->post_id,
//
//        ]);


        return $like;

    }


    function removelike(Request $request)
    {

        $count = DB::table('likesposts')
            ->where('user_id', '=', $request->user_id)
            ->Where('post_id', '=', $request->post_id)
            ->delete();

       DB::table('saveds')
            ->where('user_id', '=', $request->user_id)
            ->Where('post_id', '=', $request->post_id)
            ->delete();


        if ($count > 0) {
            DB::table('posts')
                ->where('id', $request->post_id)
                ->decrement('like_count', 1);
        }


        return $count;

    }



    function fcm($token, $title, $body)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $serverKey = 'AAAArlMHDHs:APA91bH1kt2zcM-9bYF_1ath9QUlJ0rGrzvvdYH7O2kRKNVzEx1Qt5ISPF1XyAOmrha3O_6WVPBmVd0Z4NF1nLJVqCk-bUt2ATkZePBX6qkJdagbKFG_as30KnbtbcKfXgWz3GthElgG';

        $data = [


            "to" => "$token",
            "notification" => [
                "title" => " $title Like Your Post",

                "body" => $body,
            ]
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

        // Execute post3
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        // FCM response
//        dd($result);

    }

}
