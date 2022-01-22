<?php

namespace App\Http\Controllers;

use App\comments;
use App\reportposts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class report_posts_controller extends Controller
{

    function postreport(Request  $request)
    {

        $report = reportposts::create([


            'post_id' => $request->post_id,
            'user_id' => $request->user_id,
            'reporter_id' =>  $request->reporter_id


        ]);


        return $report;
    }
}
