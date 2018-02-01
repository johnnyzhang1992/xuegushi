<?php

namespace App\Http\Controllers;

use DB;
use Auth;
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
    /**
     * 返回收藏和喜欢统计数据
     */
    public function getClAndLkCount(){
        if(!Auth::guest()){
            $user = DB::table('users')->where('id',Auth::user()->id)->first();
            $collect_count = $user->collect_count;
            $like_count = $user->like_count;
        }else{
           $collect_count = 0;
           $like_count = 0;
        }
        return [
            'like_count' => $like_count,
            'collect_count' =>$collect_count
        ];
    }
    /**
     * 判断是否已点赞
     * @param $id
     * @return bool
     */
    public function is_like($id){
        if (Auth::guest()){
            return false;
        }
        $data = DB::table('dev_like')
            ->where('like_id',$id)
            ->where('type','post_review')
            ->where('user_id',Auth::user()->id)
            ->where('status','active')
            ->first();
        if(isset($data) && $data){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 获取某个评论的点赞数
     * @param $id
     * @return int
     */
    static function getLikeCount($id){
        if (Auth::guest()){
            return 0;
        }
        $data = DB::table('dev_like')
            ->where('like_id',$id)
            ->where('user_id',Auth::user()->id)
            ->where('type','post_review')
            ->where('status','active')
            ->count();
        return isset($data) && $data ? $data : 0;
    }
}
