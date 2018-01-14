<?php
/**
 * Controller show
 * 用户前端主页controller
 */

namespace App\Http\Controllers\ZhuanLan;

use DB;
use Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class MeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 个人主页
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id){
        $me = DB::table('users')
            ->where('id',$id)
            ->first();
        if(isset($me) && $me){
            $posts = DB::table('dev_post')
                ->where('creator_id',$id)
                ->where('status','active')
                ->orderBy('id','desc')
                ->get();
            return view('zhuan.me.show')
                ->with('posts',$posts)
                ->with('me',$me)
                ->with('is_has',$this->isHasZhuanlan())
                ->with('site_title',$me->name);
        }else{
            return view('errors.404');
        }
    }

    /**
     * 我的订阅
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function subscribes($id){
        $me = DB::table('users')
            ->where('id',$id)
            ->first();
        if(isset($me) && $me){
            $zls = DB::table('dev_zl_follow')
                ->where('dev_zl_follow.u_id',$id)
                ->where('dev_zl_follow.status',1)
                ->leftJoin('dev_zhuanlan','dev_zhuanlan.id','=','dev_zl_follow.zl_id')
                ->select('dev_zl_follow.updated_at as time','dev_zhuanlan.*')
                ->orderBy('dev_zl_follow.id','desc')
                ->get();
            if(isset($zls) && $zls){
                foreach ($zls as $key=>$zl){
                    $zls[$key]->post_count = $this->getZLPostCount($zl->id);
                    $zls[$key]->followers_count = $this->getZLFollowCount($zl->id);
                }
            }
            return view('zhuan.me.subscribes')
                ->with('zls',$zls)
                ->with('me',$me)
                ->with('is_has',$this->isHasZhuanlan())
                ->with('site_title',$me->name.'的订阅');
        }else{
            return view('errors.404');
        }
    }
    /**
     * 我的草稿
     * @return mixed
     */
    public function drafts(){
        $posts = DB::table('dev_post')
            ->where('creator_id',Auth::user()->id)
            ->where('status','draft')
            ->orderBy('id','desc')
            ->get();
        return view('zhuan.post.draft')
            ->with('status','drafts')
            ->with('is_has',$this->isHasZhuanlan())
            ->with('site_title','我的草稿')
            ->with('posts',$posts);
    }

    /**
     * 我的文章
     * @return mixed
     */
    public function posts(){
        $posts = DB::table('dev_post')
            ->where('creator_id',Auth::user()->id)
            ->orderBy('id','desc')
            ->get();
        return view('zhuan.post.draft')
            ->with('status','posts')
            ->with('is_has',$this->isHasZhuanlan())
            ->with('site_title','我的文章')
            ->with('posts',$posts);
    }
    /**
     * 判断当前用户是否注册过专栏
     * @return bool
     */
    public function isHasZhuanlan(){
        if (!Auth::guest()){
            $data = DB::table('dev_zhuanlan')->where('creator_id',Auth::user()->id)->count();
        }else{
            $data = 0;
        }
        if($data>0){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 获取专栏关注人数
     * @param $id
     * @return string
     */
    static function getZLFollowCount($id){
        $count = DB::table('dev_zl_follow')
            ->where('dev_zl_follow.zl_id',$id)
            ->where('dev_zl_follow.status',1)
            ->count();
        return number_format($count);
    }
    public function getZLPostCount($id){
        $count=DB::table('dev_post')
            ->where('zhuanlan_id',$id)
            ->where('status','active')
            ->count();
        return $count;
    }
}