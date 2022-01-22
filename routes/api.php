<?php

use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::post("/get_user_id_followed","usertestController@get_user_id_followed");

Route::post("/adduser","usertestController@adduser");
Route::post("/login","usertestController@login");



Route::post("/add_follow","follow_control@addfollow");

Route::post("/removefollow","follow_control@removefollow");

Route::get("/getusers","usertestController@getusers");

Route::post("/get_user_following_me","usertestController@get_user_following_me");

Route::post("/update_profile_img","usertestController@update_profile_img");

Route::post("/update_profile_image_Cloud_Run","usertestController@update_profile_image_Cloud_Run");




Route::post("/update_profile_name","usertestController@update_profile_name");

Route::post("/update_profile_bio","usertestController@update_profile_bio");


Route::get("/getuser","usertestController@getUserData");



//Posts
Route::post("/addpost","posts@addpost");
Route::get("/get_user_followed_posts","posts@get_user_followed_posts");

Route::post("/get_user_followed_posts_V3","posts@get_user_followed_posts_V3");

Route::post("/get_user_followed_videos","posts@get_user_followed_videos");

Route::post("/addlike","likesposts@addlike");
Route::post("/removelike","likesposts@removelike");

Route::post("/delete_post","posts@delete_post");
Route::post("/getpost_type","posts@getpost_type");
Route::post("/getpost_type2","posts@getpost_type2");



Route::post("/getpost_for_you","posts@getpost_for_you");


Route::post("/get_post_by_postid","posts@get_post_by_postid");


Route::post("/get_post_by_user_id","posts@get_post_by_user_id");
Route::post("/get_post","posts@get_post");
Route::get("/test","posts@test");












//comments
Route::post("/addcomment","comments_control@addcomment");
Route::post("/deletecomment","comments_control@deletecomment");
Route::post("/getcomments","comments_control@getcomments");


//report
Route::post("/postreport","report_posts_controller@postreport");




//lastconversations


Route::get("/getConversation","LastConversationController@getLastConversation");

Route::get("/insertToLastConversation","LastConversationController@insertToLastConversation");

