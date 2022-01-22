<?php


namespace App\Http\Controllers;


use App\last_conversations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LastConversationController extends \Illuminate\Routing\Controller
{
    function getLastConversation(Request $request)
    {
        $notices = DB::table('usertests')
            ->select(array("usertests.*"))
            ->join('last_conversations', 'usertests.user_id', '='
                , 'last_conversations.receiverId')
            ->where('last_conversations.senderId', '=', (string)$request->senderId)


//            ->where('usertests.user_id', '!=', (string)$request->senderId)

            ->get();


        return $notices;

    }






    function insertToLastConversation(Request $request)
    {


        $insertToLastConversation = last_conversations::updateOrCreate([
            'senderId' => $request->senderId,
            'receiverId' => $request->receiverId
        ]);

        $insertToLastConversation = last_conversations::updateOrCreate([
            'senderId' => $request->receiverId,
            'receiverId' => $request->senderId
        ]);


//        $instance = new SendFcm;
//
//        $instance->FcmToken($request->followed_token, $request->user_name, " Following you");


        return $insertToLastConversation;


    }

}
