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
//        $this->middleware('auth');
    }

    /**
     * 个人主页
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id){
        $me = DB::table('users')->where('domain',$id)->first();
        if(isset($me) && $me){
            DB::table('users')->where('domain',$id)->increment("pv_count");
        }elseif(is_numeric( $id ) ){
            $me = DB::table('users')->where('id',$id)->first();
            DB::table('users')->where('id',$id)->increment("pv_count");
        }
        if(isset($me) && $me){
            $posts = DB::table('dev_post')
                ->where('creator_id',$me->id)
                ->where('status','active')
                ->orderBy('id','desc')
                ->get();
            if(!isset($me->domain) && strlen($me->domain)<1){
                $me->domain = $me->id;
            }
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
        $me = DB::table('users')->where('domain',$id)->first();
        if(!isset($me) && is_numeric($id)){
            $me = DB::table('users')->where('id',$id)->first();
        }
        if(isset($me) && $me){
            $zls = DB::table('dev_zl_follow')
                ->where('dev_zl_follow.u_id',$me->id)
                ->where('dev_zl_follow.status',1)
                ->leftJoin('dev_zhuanlan','dev_zhuanlan.id','=','dev_zl_follow.zl_id')
                ->select('dev_zl_follow.updated_at as time','dev_zhuanlan.*')
                ->orderBy('dev_zl_follow.id','desc')
                ->get();
            if(!isset($me->domain) && strlen($me->domain)<1){
                $me->domain = $me->id;
            }
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
     * 我的专栏
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function publications(){
        if (Auth::guest()){
            return redirect('/login');
        }
        $subs = DB::table('dev_zl_follow')
              ->where('dev_zl_follow.u_id',Auth::user()->id)
              ->where('dev_zl_follow.status',1)
              ->leftJoin('dev_zhuanlan','dev_zhuanlan.id','=','dev_zl_follow.zl_id')
              ->select('dev_zl_follow.updated_at as time','dev_zhuanlan.*')
              ->orderBy('dev_zl_follow.id','desc')
              ->get();
        if(isset($subs) && $subs){
            foreach ($subs as $key=>$zl){
                $subs[$key]->post_count = $this->getZLPostCount($zl->id);
                $subs[$key]->followers_count = $this->getZLFollowCount($zl->id);
            }
        }
        $zls = DB::table('dev_zhuanlan')
            ->where('creator_id',Auth::user()->id)
            ->orderBy('id','desc')
            ->get();
        if(isset($zls) && $zls){
            foreach ($zls as $key=>$zl){
                $zls[$key]->post_count = $this->getZLPostCount($zl->id);
                $zls[$key]->followers_count = $this->getZLFollowCount($zl->id);
            }
        }
        return view('zhuan.me.publications')
            ->with('status','posts')
            ->with('is_has',$this->isHasZhuanlan())
            ->with('site_title','我的专栏')
            ->with('zls',$zls)
            ->with('subs',$subs);
    }
    /**
     * 我的喜欢
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function favorites($id){
        $me = DB::table('users')->where('domain',$id)->first();
        if(!isset($me) && is_numeric($id)){
            $me = DB::table('users')->where('id',$id)->first();
        }
        if(isset($me) && $me){
            $posts = DB::table('dev_like')
                ->where('dev_like.type','post')
                ->where('dev_like.user_id',$me->id)
                ->where('dev_like.status','active')
                ->leftJoin('dev_post','dev_post.id','=','dev_like.like_id')
                ->select('dev_like.created_at as create_time','dev_post.*')
                ->orderBy('dev_like.created_at','desc')
                ->get();
            if(!isset($me->domain) && strlen($me->domain)<1){
                $me->domain = $me->id;
            }
            return view('zhuan.me.favorites')
                ->with('posts',$posts)
                ->with('me',$me)
                ->with('is_has',$this->isHasZhuanlan())
                ->with('site_title',$me->name.'的喜欢');
        }else{
            return view('errors.404');
        }
    }
    /**
     * 我的收藏
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function collects($id){
        $me = DB::table('users')->where('domain',$id)->first();
        if(!isset($me) && is_numeric($id)){
            $me = DB::table('users')->where('id',$id)->first();
        }
        if(isset($me) && $me){
            $posts = DB::table('dev_collect')
                ->where('dev_collect.type','post')
                ->where('dev_collect.user_id',$me->id)
                ->where('dev_collect.status','active')
                ->leftJoin('dev_post','dev_post.id','=','dev_collect.like_id')
                ->select('dev_collect.created_at as create_time','dev_post.*')
                ->orderBy('dev_collect.created_at','desc')
                ->get();
            if(!isset($me->domain) && strlen($me->domain)<1){
                $me->domain = $me->id;
            }
            return view('zhuan.me.collects')
                ->with('posts',$posts)
                ->with('me',$me)
                ->with('is_has',$this->isHasZhuanlan())
                ->with('site_title',$me->name.'的收藏');
        }else{
            return view('errors.404');
        }
    }
    /**
     * 我的评论
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function comments($id){
        $me = DB::table('users')->where('domain',$id)->first();
        if(!isset($me) && is_numeric($id)){
            $me = DB::table('users')->where('id',$id)->first();
        }
        if(isset($me) && $me) {
            if(!isset($me->domain) && strlen($me->domain)<1){
                $me->domain = $me->id;
            }
            return view('zhuan.me.comments')
                ->with('me', $me)
                ->with('posts',[])
                ->with('is_has', $this->isHasZhuanlan())
                ->with('site_title', $me->name . '的回复');
        }else{
            return view('errors.404');
        }
    }
    /**
     * 我的草稿
     * @return mixed
     */
    public function drafts(){
        if (Auth::guest()){
            return redirect('/login');
        }
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
        if (Auth::guest()){
            return redirect('/login');
        }
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
     * 我的设置
     * @return mixed
     */
    public function setting(){
        if (Auth::guest()){
            return redirect('/login');
        }
        $me = DB::table('users')
            ->where('id',Auth::user()->id)
            ->first();
        return view('zhuan.me.setting')
            ->with('is_has',$this->isHasZhuanlan())
            ->with('me',$me)
            ->with('site_title','账号设置');
    }

    /**
     * 更新我的设置
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSetting(Request $request){
        // 每次仅更新某一个值
        $_data = [];
        if (Auth::guest()){
           $_data['msg'] = '你没有登录哦！';
           $_data['status'] = false;
           return response()->json($_data);
        }
        $name = $request->input('name');
        $val = $request->input('val');
        if($name == 'name'){
            // 判断name是否已存在
            $_name =  DB::table('users')
                ->where('id',Auth::user()->id)
                ->where('name',$val)
                ->first();
            if($_name){
                $_data['msg'] = '你输入的用户名已存在！';
                $_data['status'] = false;
                return response()->json($_data);
            }
        }
        if($name == 'domain'){
            // 判断name是否已存在
            $_name =  DB::table('users')
                ->where('id',Auth::user()->id)
                ->where('domain',$val)
                ->first();
            if($_name){
                $_data['msg'] = '你输入的域名已存在！';
                $_data['status'] = false;
                return response()->json($_data);
            }
        }
        $res = DB::table('users')
            ->where('id',Auth::user()->id)
            ->update([
               $name => $val,
                'updated_at' => date('Y-m-d H:i:s',time())
            ]);
        if($res){
            $_data['msg'] = '更新成功';
            $_data['status'] = true;
        }else{
            $_data['msg'] = '更新失败';
            $_data['status'] = false;
        }
        return response()->json($_data);
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
    /**
     * 获取专栏文章数量
     * @param $id
     * @return mixed
     */
    public function getZLPostCount($id){
        $count=DB::table('dev_post')
            ->where('zhuanlan_id',$id)
            ->where('status','active')
            ->count();
        return $count;
    }
}