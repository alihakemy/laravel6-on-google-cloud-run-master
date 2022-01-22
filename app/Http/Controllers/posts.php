<?php

namespace App\Http\Controllers;


use App\usertest;
use functional\Append;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\Types\Null_;
use PhpParser\Node\Scalar\String_;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Facades\Http;


class posts extends Controller
{


    function addpost(Request $request)
    {


        $content = "";

        if (!empty($request->text)) {
            $content = $request->text;
        }


        //TODO :IncrementPostsCount
        DB::table('usertests')
            ->where('user_id', $request->user_id)
            ->increment('PostsCount', 1);


        if ($request->type == 1) {
            $post = \App\posts::create([

                "like_count" => $request->like_count,

                'content' => $content,
                'type' => $request->type,
                'video' => "",
                "img" =>
                    $request->img,
                "post_user_id" => $request->user_id


            ]);


//            $this->fcm($request->topic, $request->name, $request->text, "https://www.itsolutionstuff.com/upload/laravel-firebase-push-notification.png");

            return response()->json([$post], 200);

            //TODO Go daddy work correct


////alisami
//            $this->validate($request, [
//                'img' => 'required|image|mimes:jpeg,png,jpg,bmp,gif,gif,svg|max:2048',
//            ]);
//
//            $image = $request->file('img');
//            $name = time() . '.' . $image->extension();
//            $destinationPath = public_path('/uploads/');
//            $image->move($destinationPath, $name);
//
//            $post = \App\posts::create([
//
//                "like_count" => $request->like_count,
//                'content' => $content,
//                'type' => $request->type, 'video'
//                => $request->video, 'img' => Config::get('app.url') . "/ichat/public/uploads/$name",
//                "post_user_id" => $request->user_id
//
//
//            ]);
//            $this->fcm($request->topic, $request->name
//                , $request->text, "https://www.itsolutionstuff.com/upload/laravel-firebase-push-notification.png");
//
//            return response()->json([$post], 200);

        } else if ($request->type == 2) {


            $video = $request->file('video');
            $name = time() . '.' . "mp4";
            $destinationPath = public_path('/uploads/');
            $video->move($destinationPath, $name);

            $post = \App\posts::create([

                "like_count" => $request->like_count,
                'content' => $content,
                'type' => 2, 'video' => Config::get('app.url') . "/ichat/public/uploads/$name",
                "img" =>
                    $request->img,
                "post_user_id" => $request->user_id


            ]);

            $this->fcm($request->topic, $request->name, $request->text, "https://www.itsolutionstuff.com/upload/laravel-firebase-push-notification.png");

            DB::table('usertests')
                ->where('user_id', '=', $request->user_id)
                ->update(array('active_video' => $post->id));


            return response()->json([$post], 200);


        } else {
            $post = \App\posts::create([

                "like_count" => $request->like_count,

                'content' => $content,
                'type' => $request->type,
                'video' => "",
                "img" =>
                    "",
                "post_user_id" => $request->user_id


            ]);


//            $this->fcm($request->topic, $request->name, $request->text, "https://www.itsolutionstuff.com/upload/laravel-firebase-push-notification.png");

            return response()->json([$post], 200);

        }


    }

    function get_user_followed_videos(Request $request)
    {


        $posts = DB::table('posts')
            ->select(

                array("posts.*", "usertests.*", 'likesposts.liked')
            )
            ->join('follow_tables', 'posts.post_user_id', '=', 'follow_tables.follow')
            ->join('usertests', 'usertests.user_id', '=', 'follow_tables.follow')
            ->leftJoin('likesposts', function ($join) use ($request) {
                $join->on('likesposts.post_id', '=', 'posts.id')
                    ->where('likesposts.user_id', '=', (string)$request->id);
            })
            ->where('follow_tables.id', '=', (string)$request->id)
            ->where('posts.type', '!=', '1')
            ->orderBy('posts.created_at', 'desc')
            ->paginate(15);


        return response()->json($posts, 200);


    }

    //getpost from  user iam following
    function get_user_followed_posts(Request $request)
    {





        $posts = DB::table('posts')
            ->select(

                array("posts as posts.* posts.created_at as post_created_at ", "usertests.*", 'likesposts.liked')
            )
            ->join('follow_tables', 'posts.post_user_id', '=', 'follow_tables.follow')
            ->join('usertests', 'usertests.user_id', '=', 'follow_tables.follow')
            ->leftJoin('likesposts', function ($join) use ($request) {
                $join->on('likesposts.post_id', '=', 'posts.id')
                    ->where('likesposts.user_id', '=', (string)$request->user_id);
            })
            ->where('follow_tables.id', '=', (string)$request->user_id)
            ->where('posts.type', '!=', '2')
            ->orderBy('posts.created_at', 'desc')

            ->paginate(20);






        // Create a new item and populate
        //ads type
        // 5 admob native
        // 6facebook native
        //
        //    $item = new \App\posts();

//
//        $item->id = "";
//        $item->content = "ca-app-pub-3940256099942544/2247696110";
//        $item->type = "5";
//        $item->video = "http://xchat.today/xchat/public/storage/postsvideos/1620735978.mp4";
//        $item->img = "";
//        $item->post_user_id = "6";
//        $item->like_count = "0";
//        $item->created_at = "2021-04-30 05:20:20";
//        $item->updated_at = "2021-04-30 05:20:20";
//        $item->user_id = "6";
//        $item->user_name = "alisami5d";
//        $item->user_email = "alisamihakemy326461@gmail.com";
//        $item->user_password = "123123";
//        $item->user_about = "ali";
//        $item->user_gender = "male";
//        $item->user_img = "http://localhost/xchat/public/storage/userImages/1619760020.jpg";
//        $item->user_follower_count = "6";
//        $item->user_following_count = "3";
//        $item->user_token = "dsfv";
//        $item->user_hobbies = "fdveref";
//        $item->user_countery_code = "wrwr";
//        $item->user_lat = "qwe";
//        $item->user_lag = "ffsdf";
//        $item->remember_token = null;
//        $item->liked = null;
//
////
////       // Insert the new item at top
////        //$posts ->appends($item);
//        $posts->prepend($item);
        return response()->json($posts, 200);


//        //call module
//        $posts = DB::select('select posts.video ,usertests.name
//    FROM posts
//    INNER JOIN follow_tables ON posts.user_id = follow_tables.follow
//     INNER JOIN usertests ON usertests.id = 8
//and  follow_tables.id = 8
//
//    ');
//
//        $result = new Paginator($posts, 100, 1, []);
        //   return $result;

    }

    /**
     * @param Request $request
     * @param post_id
     * @param user_id this user is post owner
     * @return int
     */
    function delete_post(Request $request)
    {


        return DB::table('posts')
            ->where('post_user_id', '=', $request->user_id)
            ->Where('id', '=', $request->post_id)
            ->delete();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * use this to get video in android
     * DisCover
     */
    function getpost_type(Request $request)
    {

//        $ip = $request->ip();
//        $data = Location::get($ip);
//        $latitude = $data->latitude;
//        $longitude = $data->longitude;
//        $selectDistance =
//            '( 6371  * acos( cos( radians(' . $latitude . ') ) ' .
//            '* cos( radians( usertests.user_lat ) ) ' .
//            '* cos( radians( usertests.user_lag ) - radians(' . $longitude . ') ) ' .
//            '+ sin( radians(' . $latitude . ') ) ' .
//            '* sin( radians( usertests.user_lat ) ) ) )';
//
//        $posts = DB::table('posts')
//            ->select(
//
//                array("posts.*", "usertests.*", 'likesposts.liked')
//            )
//            ->join('usertests', 'usertests.user_id', '=', 'posts.post_user_id')
//            ->leftJoin('likesposts', function ($join) use ($request) {
//                $join->on('likesposts.post_id', '=', 'posts.id')
//                    ->where('likesposts.user_id', '=', (string)$request->id);
//            })
//
//            ->orderByRaw("{$selectDistance} asc ")
//
////           ->inRandomOrder()
//
//
//            ->where('posts.type', '=', $request->type)
//
//            ->paginate(6);
//
//
//
//
//        return response()->json([$posts], 200);


//        $products = DB::table('posts')
//            ->join('usertests', 'posts.post_user_id', '=', 'usertests.user_id')
//            ->select(array("posts.*", "usertests.*"))
//            ->where('posts.type', '=', $request->type)
//            ->orderBy('posts.created_at', 'desc')
//            ->paginate(15);
//        return response()->json([$products], 200);


        $ip = $request->ip();
        $data = Location::get($ip);
        $latitude = $data->latitude;
        $longitude = $data->longitude;
        $selectDistance =
            '( 6371  * acos( cos( radians(' . $latitude . ') ) ' .
            '* cos( radians( usertests.user_lat ) ) ' .
            '* cos( radians( usertests.user_lag ) - radians(' . $longitude . ') ) ' .
            '+ sin( radians(' . $latitude . ') ) ' .
            '* sin( radians( usertests.user_lat ) ) ) )';

        $posts = DB::table('posts')
            ->select(

                array("posts.*", "usertests.*", 'likesposts.liked')
            )
            ->join('usertests', 'usertests.active_video', '=', 'posts.id')
            ->leftJoin('likesposts', function ($join) use ($request) {
                $join->on('likesposts.post_id', '=', 'posts.id')
                    ->where('likesposts.user_id', '=', (string)$request->id);
            })
            ->orderBy('posts.created_at', 'desc')

            //->orderByRaw("{$selectDistance} asc ")

//           ->inRandomOrder()


            ->where('posts.type', '=', $request->type)
            ->paginate(6);


        return response()->json([$posts], 200);

        //return response()->json([$posts], 200);


    }


    function getpost_for_you(Request $request)
    {

        //Haversine formula
        $ip = $request->ip();
        $data = Location::get($ip);

        if ($data != false) {


            $latitude = $data->latitude;
            $longitude = $data->longitude;
            $radius = 10000;


            $selectDistance =
                '( 6371  * acos( cos( radians(' . $latitude . ') ) ' .
                '* cos( radians( usertests.user_lat ) ) ' .
                '* cos( radians( usertests.user_lag ) - radians(' . $longitude . ') ) ' .
                '+ sin( radians(' . $latitude . ') ) ' .
                '* sin( radians( usertests.user_lat ) ) ) )';

            $products = DB::table('posts')
                ->join('usertests', 'posts.post_user_id', '=', 'usertests.user_id')
                ->select(array("posts.*", "usertests.*"))
                ->where('posts.type', '!=', '2')
                ->selectRaw("{$selectDistance} AS distance")
                ->whereRaw("{$selectDistance} < ?", $radius)
                ->orderByRaw("{$selectDistance} asc ")
                ->paginate(25);


            return response()->json([$products], 200);
        } else {

            $products = DB::table('posts')
                ->join('usertests', 'posts.post_user_id', '=', 'usertests.user_id')
                ->select(array("posts.*", "usertests.*"))
                ->where('posts.type', '!=', '2')
                ->paginate(25);
            return response()->json([$products], 200);
        }
    }


    function  test ()
    {   $posts = DB::table('posts')
        ->select("posts.*")->get();

        return response()->json([$posts], 200);

    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * use in android to show post details
     */
    function get_post_by_postid(Request $request)
    {


        $posts = DB::table('posts')
            ->select(

                array("posts.*", "usertests.*", 'likesposts.liked')
            )
            ->join('usertests', 'posts.post_user_id', '=', 'usertests.user_id')
            ->leftJoin('likesposts', function ($join) use ($request) {
                $join->on('likesposts.post_id', '=', 'posts.id')
                    ->where('likesposts.user_id', '=', (string)$request->user_id);
            })
            ->where("posts.id", "=", $request->post_id)
            ->paginate(25);


        return response()->json([$posts], 200);


    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * to get posts for each user
     */
    function get_post_by_user_id(Request $request)
    {


        $posts = DB::table('posts')
            ->select(

                array("posts.*", "usertests.*", 'likesposts.liked')
            )
            ->join('usertests', 'posts.post_user_id', '=', 'usertests.user_id')
            ->leftJoin('likesposts', function ($join) use ($request) {
                $join->on('likesposts.post_id', '=', 'posts.id')
                    ->where('likesposts.user_id', '=', (string)$request->sender_id);
            })
            ->where("posts.post_user_id", "=", $request->user_id)
            ->orderBy('posts.created_at', 'desc')
            ->paginate(20);


        return response()->json($posts, 200);


    }


    function fcm($topic, $title, $body, $icon)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $serverKey = 'AAAArlMHDHs:APA91bH1kt2zcM-9bYF_1ath9QUlJ0rGrzvvdYH7O2kRKNVzEx1Qt5ISPF1XyAOmrha3O_6WVPBmVd0Z4NF1nLJVqCk-bUt2ATkZePBX6qkJdagbKFG_as30KnbtbcKfXgWz3GthElgG';

        $data = [


            "to" => "/topics/$topic",
            "notification" => [
                "title" => " $title Add New Post",
                "image" => $icon,
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


    function get_post(Request $request)
    {


        $limit = 10;
        $offset = 1;

        $id = (string)$request->id;
        $notices = DB::select(" select usertests.*
from usertests
left join follow_tables f
  on  f.id <> usertests.user_id
  and f.follow = usertests .user_id  and f.id= '$id'
where usertests .user_id <> '$id'
  and f.id is null LIMIT $offset,10  ");

        $posts = DB::table('posts')
            ->select(

                array("posts.*", "usertests.*", 'likesposts.liked')
            )
            ->join('follow_tables', 'posts.post_user_id', '=', 'follow_tables.follow')
            ->join('usertests', 'usertests.user_id', '=', 'follow_tables.follow')
            ->leftJoin('likesposts', function ($join) use ($request) {
                $join->on('likesposts.post_id', '=', 'posts.id')
                    ->where('likesposts.user_id', '=', (string)$request->id);
            })
            ->where('follow_tables.id', '=', (string)$request->id)
            ->where('posts.type', '!=', '2')
            ->orderBy('posts.created_at', 'desc')
            ->paginate(15);


        $user["suggest"] = [$notices];


        //  $posts->push(['suggest' => $user]); //its adding array instead object

        return response()->json([$posts, $user], 200);


    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * use this to get video in android
     * DisCover
     */
    function getpost_type2(Request $request)
    {


        $ip = $request->ip();
        $data = Location::get($ip);
        $latitude = $data->latitude;
        $longitude = $data->longitude;
        $selectDistance =
            '( 6371  * acos( cos( radians(' . $latitude . ') ) ' .
            '* cos( radians( usertests.user_lat ) ) ' .
            '* cos( radians( usertests.user_lag ) - radians(' . $longitude . ') ) ' .
            '+ sin( radians(' . $latitude . ') ) ' .
            '* sin( radians( usertests.user_lat ) ) ) )';

        $posts = DB::table('posts')
            ->select(

                array("posts.*", "usertests.*", 'likesposts.liked')
            )
            ->join('usertests', 'usertests.active_video', '=', 'posts.id')
            ->leftJoin('likesposts', function ($join) use ($request) {
                $join->on('likesposts.post_id', '=', 'posts.id')
                    ->where('likesposts.user_id', '=', (string)$request->id);
            })
            ->orderBy('posts.created_at', 'desc')
            ->where('posts.type', '=', $request->type)
            ->paginate(6);


        $ip = $request->ip();
        $data = Location::get($ip);
        $latitude = $data->latitude;
        $longitude = $data->longitude;
        $selectDistance =
            '( 6371  * acos( cos( radians(' . $latitude . ') ) ' .
            '* cos( radians( usertests.user_lat ) ) ' .
            '* cos( radians( usertests.user_lag ) - radians(' . $longitude . ') ) ' .
            '+ sin( radians(' . $latitude . ') ) ' .
            '* sin( radians( usertests.user_lat ) ) ) )';

        $limit = 20;
        $offset = ($limit * $request->page) - $limit;

        $id = (string)$request->id;
        $notices = DB::select(" select usertests.*
from usertests
left join follow_tables f
  on  f.id <> usertests.user_id
  and f.follow = usertests .user_id  and f.id= '$id'
where usertests .user_id <> '$id'
  and f.id is null ORDER BY usertests.PostsCount DESC ,{$selectDistance} asc LIMIT $offset,20  ");


//        $usertemp =$result_array["suggest_users"] = $notices;
//
//        for ($i=0; $i<count($posts); $i++)
//        {
//            if ($i==1)
//            {
//                json_decode( (string) $usertemp,true);
//            }
//            $result_array[] = $posts[$i];
//        }
//
//
//
//        return response()->json([$array , 200]);
//

    }

    //getpost from  user iam following
    function get_user_followed_posts_V3(Request $request)
    {


        $posts = DB::table('posts')
            ->select(

                array("posts.*", "usertests.*", 'likesposts.liked')
            )
            ->join('follow_tables', 'posts.post_user_id', '=', 'follow_tables.follow')
            ->join('usertests', 'usertests.user_id', '=', 'follow_tables.follow')
            ->leftJoin('likesposts', function ($join) use ($request) {
                $join->on('likesposts.post_id', '=', 'posts.id')
                    ->where('likesposts.user_id', '=', (string)$request->id);
            })
            ->where('follow_tables.id', '=', (string)$request->id)
            ->orderBy('posts.created_at', 'desc')
            ->paginate(15);


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
        $data = Location::get($ip);
        $latitude = $data->latitude;
        $longitude = $data->longitude;
        $selectDistance =
            '( 6371  * acos( cos( radians(' . $latitude . ') ) ' .
            '* cos( radians( usertests.user_lat ) ) ' .
            '* cos( radians( usertests.user_lag ) - radians(' . $longitude . ') ) ' .
            '+ sin( radians(' . $latitude . ') ) ' .
            '* sin( radians( usertests.user_lat ) ) ) )';

        $limit = 20;
        $offset = ($limit * $request->page) - $limit;

        $id = (string)$request->id;
        $notices = DB::select(" select usertests.*
from usertests
left join follow_tables f
  on  f.id <> usertests.user_id
  and f.follow = usertests .user_id  and f.id= '$id'
where usertests .user_id <> '$id'
  and f.id is null ORDER BY usertests.PostsCount DESC ,{$selectDistance} asc LIMIT $offset,20  ");


//        $limit = 10;
//        $offset = ($limit * $request->page) - $limit;
//
//        $id = (string) $request->id;
//        $notices = DB::select("select * from usertests as   u
//LEFT JOIN follow_tables as f ON u.user_id = f.id OR u.user_id = f.follow
//WHERE u.user_id = '$id'
//    AND f.id IS NULL
//    OR f.follow IS NULL   ");

















        $collection = collect($posts->items());
        $merged     = $collection->merge(["users",$notices]);
        $result[]   = $merged->all();

        return response()->json([$result], 200);


    }


















}
