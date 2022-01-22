<?php

namespace App\Http\Controllers;

use App\follow_tabel;
use App\follow_tables;
use App\usertest;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stevebauman\Location\Facades\Location;

class usertestController extends Controller
{


    function update_profile_img(Request $request)
    {


//        // select than update
//        $this->validate($request, [
//            'user_img' => 'required|image|mimes:jpeg,png,jpg,bmp,gif,svg|max:20000',
//        ]);
//
//        $image = $request->file('user_img');
//        $name = time() . '.' . $image->getClientOriginalExtension();
//        $destinationPath = public_path('/uploads/');
//        $image->move($destinationPath, $name);
//        DB::table('usertests')
//            ->where('user_id', '=', $request->user_id)
//            ->update(array('user_img' => Config::get('app.url') . "/ichat/public/uploads/$name"));
//
//
//        $user = DB::table('usertests')
//            ->where('user_id', '=', $request->user_id)->get();
//
//        return response()->json($user, 200);

    }

    function update_profile_image_Cloud_Run(Request $request)
    {


        DB::table('usertests')
            ->where('user_id', '=', $request->userId)
            ->update(array('user_img' => $request->userImage));


        $user = DB::table('usertests')
            ->where('user_id', '=', $request->userId)->get();

        return response()->json($user, 200);

    }

    function update_profile_name(Request $request)
    {


        DB::table('usertests')
            ->where('user_id', '=', $request->user_id)
            ->update(array('user_name' => $request->user_name));


        $user = DB::table('usertests')
            ->where('user_id', '=', $request->user_id)->get();

        return response()->json($user, 200);

    }


    function update_profile_bio(Request $request)
    {


        DB::table('usertests')
            ->where('user_id', '=', $request->user_id)
            ->update(array('user_about' => $request->user_about));


        $user = DB::table('usertests')
            ->where('user_id', '=', $request->user_id)->get();

        return response()->json($user, 200);

    }

    //to get user data by id in ichat is phone number or email
    function  getUserData(Request $request)
    {
        $user = DB::table('usertests')
            ->where('user_id', '=', $request->user_id)->get();

        return response()->json($user, 200);

    }

    //to get user data by id in ichat is phone number or email
    function  login(Request $request)
    {
        $user = DB::table('usertests')
            ->where('phoneNo', '=', $request->phone)
            ->where('user_password','=',$request->password)
            ->get();

        return response()->json($user, 200);

    }





    //get users i followed expect my account
    function get_user_id_followed(Request $request)
    {

        //call module
        $notices = DB::table('usertests')
            ->select(array("usertests.*"))
            ->join('follow_tables', 'usertests.user_id', '=', 'follow_tables.follow')
            ->where('follow_tables.id', '=', (string)$request->user_id)
            ->where('usertests.user_id', '!=', (string)$request->user_id)
            ->orderBy('usertests.created_at', 'desc')

            ->paginate(20);


//        //call module
//        $notices = DB::select('select usertests.name
//    FROM usertests
//    INNER JOIN follow_tables ON usertests.id = follow_tables.follow
//and  follow_tables.id = $request->id
//
//    ');
//
//        $result = new Paginator($notices,100,1,[]);
//


        return response()->json($notices, 200);
    }


    function get_user_following_me(Request $request)
    {

        //call module
        $notices = DB::table('usertests')
            ->select(array("usertests.*"))
            ->join('follow_tables', 'usertests.user_id', '=', 'follow_tables.id')
            ->where('follow_tables.follow', '=', (string)$request->id)
            ->where('usertests.user_id', '!=', (string)$request->id)
            ->orderBy('usertests.created_at', 'desc')

            ->paginate(25);


        return response()->json([$notices], 200);
    }


    function getusers(Request $request)
    {
//
//        //call module
//        $notices = DB::select('  SELECT user_id from usertests WHERE user_id != 1
//                       AND user_id NOT IN
//                        ( SELECT follow_tables.follow from follow_tables where follow_tables.id=1)
//
//
//');
//


        $ip = $request->ip();
//        $data = Location::get($ip);
//        $latitude = $data->latitude;
//        $longitude = $data->longitude;
//        $selectDistance =
//            '( 6371  * acos( cos( radians(' . $latitude . ') ) ' .
//            '* cos( radians( usertests.user_lat ) ) ' .
//            '* cos( radians( usertests.user_lag ) - radians(' . $longitude . ') ) ' .
//            '+ sin( radians(' . $latitude . ') ) ' .
//            '* sin( radians( usertests.user_lat ) ) ) )';

        $limit = 20;
        $offset = ($limit * $request->page) - $limit;

        $id = (string)$request->user_id;
        $notices = DB::select(" select usertests.*
from usertests
left join follow_tables f
  on  f.id <> usertests.user_id
  and f.follow = usertests .user_id  and f.id= '$id'
where usertests .user_id <> '$id'
  and f.id is null ORDER BY usertests.PostsCount DESC  LIMIT $offset,20  ");


//        $limit = 10;
//        $offset = ($limit * $request->page) - $limit;
//
//        $id = (string) $request->id;
//        $notices = DB::select("select * from usertests as   u
//LEFT JOIN follow_tables as f ON u.user_id = f.id OR u.user_id = f.follow
//WHERE u.user_id = '$id'
//    AND f.id IS NULL
//    OR f.follow IS NULL   ");


        return response()->json($notices, 200);

    }


    function adduser(Request $request)
    {




        $user = DB::table('usertests')->where('user_id', '=', $request->user_id)->first();
        if ($user === null) {
            // user doesn't exist


            // $this->validate($request, [
            //     'user_img' => 'required|image|mimes:jpeg,png,jpg,bmp,gif,svg|max:10048',
            // ]);

            // $image = $request->file('user_img');
            // $name = time() . '.' . $image->getClientOriginalExtension();
            // $destinationPath = public_path('/storage/userImages/');
            // $image->move($destinationPath, $name);

            $user = usertest::updateOrCreate([
                'user_id' => $request->user_id,
                'user_name' => $request->user_name,
                'user_email' => $request->user_email,
                'user_password' => $request->user_password,
                'user_about' => $request->user_about,
                'user_gender' => $request->user_gender,
                'user_img' => $request->user_img,
                'phoneNo' => $request->user_phone,

                'active_video' => -1,
                'user_follower_count' => 0,
                'user_following_count' => 0,
                'user_token' => $request->user_token,
                'user_hobbies' => $request->user_hobbies,
                'user_countery_code' => "fd",
                'user_lat' => "fs",
                'user_lag' => "Sfd"

            ]);
//            $this->fcm("join",
//                $request->user_name,
//                " $request->user_name  Joined To XChat",
//                $request->user_img);


            $follow = follow_tables::updateOrCreate([
                'id' => $request->user_id,
                'follow' => $request->user_id
            ]);

            return response()->json([$user], 200);

        } else {


            DB::table('usertests')
                ->where('user_id', $request->user_id)
                ->update(array('user_token' => $request->user_token));

            $user = DB::table('usertests')
                ->select(array("usertests.*"))
                ->where('user_id', '=', $request->user_id)
                ->get();

//            $this->fcm("join",
//                $user[0]->user_name,
//                " Back To XChat",
//                $user[0]->user_img);

            $follow = follow_tables::updateOrCreate([
                'id' => $request->user_id,
                'follow' => $request->user_id
            ]);
            return response()->json($user, 200);
        }


        // Mail::send([], [], function ($message) use ($request){
        //     $message->to($request->user_email, 'Tutorials Point')
        //         ->subject('subject')

        //         ->setBody('some body', 'text/html');
        //     $message->from('support@xchat.today','Xcompany server');
        // });

        //return [$data];


    }





}
