<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public function  __construct () {

    }

    /**
     * 返回热门诗人
     */
    public function getHotAuthors(){
        $hot_poems = DB::table('dev_author')->orderBy('like_count','desc')->paginate(12);
        return $hot_poems;
    }
}
