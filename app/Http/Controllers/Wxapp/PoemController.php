<?php

namespace App\Http\Controllers\Wxapp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Cache;
use Carbon;
use DB;
use Log;
use Hash;
use Auth;
use Config;
use \stdClass;
//use phpDocumentor\Reflection\Types\Array_;


class PoemController extends Controller {

    /**
     * 验证微信token的有效性
     * @param $token
     * @param $u_id
     * @return boolean
     */
    public function validateWxToken($u_id,$token){
        $user = DB::table('users')->where('id',$u_id)->first();
        if(isset($user) && $user->wx_token == $token){
            // 验证通过
            return true;
        }else{
            return false;
        }
    }
}