<?php

namespace App\Http\Controllers;


use App\comments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class comments_control extends Controller
{

    function  addcomment(Request  $request)
    {

        if ($request->type == 1) {



            $this->validate($request, [
                'img' => 'required|image|mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',
            ]);

            $image = $request->file('img');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/storage/commentsmedia/');
            $image->move($destinationPath, $name);

            $comment = comments::create([


                'comment_text' => $request->comment_text,
                'comment_type' => $request->comment_type,
                'comment_media' =>   Config::get('app.url') . "/xchat/public/storage/commentsmedia/$name",
                "comment_user_id" => $request->comment_user_id,
                "comment_post_id" => $request->comment_post_id


            ]);
            return  response()->json($comment, 200) ;
        } else if ($request->type == 2) {



            $video = $request->file('video');
            $name = time() . '.' . "mp4";
            $destinationPath = public_path('/storage/commentsmedia/');
            $video->move($destinationPath, $name);

            $comment= comments::create([

                'comment_text' => $request->comment_text,
                'comment_type' => $request->comment_type,
                'comment_media' =>   Config::get('app.url') . "/xchat/public/storage/commentsmedia/$name",
                "comment_user_id" => $request->comment_user_id,
                "comment_post_id" => $request->comment_post_id

            ]);
            return  response()->json($comment, 200) ;





        } else {

//            for ($x = 0; $x <= 1000; $x++) {
                $comment = comments::create([

                    'comment_text' => $request->comment_text,
                    'comment_type' => $request->comment_type,
                    'comment_media' => "",
                    "comment_user_id" => $request->comment_user_id,
                    "comment_post_id" => $request->comment_post_id


                ]);
                $this->fcm($request->comment_user_token,$request->user_name,$request->comment_text);


//            }
            return  response()->json($comment, 200) ;

        }

    }


    function  deletecomment(Request $request)
    {

        $count = DB::table('comments')
            ->where('comment_user_id', '=', $request->comment_user_id)
            ->Where('comment_post_id', '=', $request->comment_post_id)
            ->delete();


        return $count;
    }


    function  getcomments(Request  $request)
    {
        $notices = DB::table('comments')
            ->select(

                array("comments.*", "usertests.*")
            )
            ->join('usertests', 'usertests.user_id', '=', 'comment_user_id')

            ->where('comment_post_id', '=', $request->comment_post_id)
            ->orderBy('comments.created_at', 'desc')
            ->paginate(15);

        return  response()->json([ $notices], 200) ;
    }


    function fcm($token, $title, $body)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $serverKey = 'AAAArlMHDHs:APA91bH1kt2zcM-9bYF_1ath9QUlJ0rGrzvvdYH7O2kRKNVzEx1Qt5ISPF1XyAOmrha3O_6WVPBmVd0Z4NF1nLJVqCk-bUt2ATkZePBX6qkJdagbKFG_as30KnbtbcKfXgWz3GthElgG';

        $data = [


            "to" => "$token",
            "notification" => [
                "title" => " $title Comments on  Your Post",

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
