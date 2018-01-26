<?php
/**
 * Controller show
 * 专栏 controller
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
class ZhuanLanController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * 专栏首页
     * @return $this
     */
    public function index(){
        $zhuanlans = DB::table('dev_zhuanlan')
            ->paginate(8);
        foreach ($zhuanlans as $key=>$zhuan){
            $zhuanlans[$key]->post_count = DB::table('dev_post')
                ->where('zhuanlan_id',$zhuan->id)
                ->where('status','active')
                ->count();
            $zhuanlans[$key]->follow_count = $this->getZLFollowCount($zhuan->id);
        }
        $posts = DB::table('dev_post')
            ->where('dev_post.status','active')
            ->leftJoin('users','users.id','=','dev_post.creator_id')
            ->select('dev_post.*','users.name as author_name')
            ->orderBy('dev_post.created_at','desc')
            ->orderBy('dev_post.pv_count','desc')
            ->paginate(6);
        return view('zhuan.index')
            ->with('query','home')
            ->with('zhuans',$zhuanlans)
            ->with('posts',$posts)
            ->with('is_has',$this->isHasZhuanlan());
    }

    /**
     * 专栏申请页面
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function apply(){
        if (Auth::guest()){
            return redirect('/login');
        }
        return view('zhuan.zhuanlan.create')
            ->with('is_has',$this->isHasZhuanlan());
    }

    /**
     * 专栏详情显示
     * @param $domain
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($domain){
        $data = DB::table('dev_zhuanlan')->where('name','=',$domain)->first();
        if(isset($data) && $data){
            $posts = DB::table('dev_post')
                ->where('dev_post.status','active')
                ->where('dev_post.zhuanlan_id',$data->id)
                ->leftJoin('users','users.id','=','dev_post.creator_id')
                ->select('dev_post.*','users.name as author_name')
                ->orderBy('dev_post.id','desc')->paginate(6);
            DB::table('dev_zhuanlan')->where('id',$data->id)->increment("pv_count");
            if($posts && count($posts)>0){
                foreach ($posts as $key=>$post){
                    $_desc =strip_tags($post->content);
                    $_desc= str_replace("&nbsp;"," ",$_desc);
                    $__desc = str_replace(array("&nbsp;","&amp;nbsp;","\t","\r\n","\r","\n"),array("","","","",""),$_desc);
                    $_content = mb_substr($__desc,0,80);
                    if(mb_strlen($__desc)>80){
                        $_content = $_content.'...';
                    }
                    $posts[$key]->content = $_content;


                }
            }
            return view('zhuan.zhuanlan.show')
                ->with('data',$data)
                ->with('posts',$posts)
                ->with('is_follow',$this->is_follow($data->id))
                ->with('follow_count',$this->getZLFollowCount($data->id))
                ->with('site_title',$data->alia_name)
                ->with('is_has',$this->isHasZhuanlan());
        }else{
            return view('errors.404');
        }
    }

    /**
     * 专栏关于页面
     * @param $domain
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about($domain){
        $data = DB::table('dev_zhuanlan')->where('name','=',$domain)->first();
        if(isset($data) && $data){
            $editor = DB::table('users')->where('id',$data->creator_id)->first();
            $editor->post_count = DB::table('dev_post')
                ->where('creator_id',$data->creator_id)
                ->where('zhuanlan_id',$data->id)
                ->count();
            $authors = DB::table('dev_post_relation')
                ->where('dev_post_relation.zhuanlan_id',$data->id)
                ->leftJoin('users','users.id','=','dev_post_relation.creator_id')
                ->select('dev_post_relation.creator_id','users.*')
                ->distinct('users.id')
                ->get();
            foreach ($authors as $key=>$author){
                $authors[$key]->post_count = DB::table('dev_post')
                    ->where('creator_id',$author->id)
                    ->where('zhuanlan_id',$data->id)
                    ->count();
            }
            return view('zhuan.zhuanlan.about')
                ->with('zhuan',$data)
                ->with('editor',$editor)
                ->with('authors',$authors)
                ->with('is_follow',$this->is_follow($data->id))
                ->with('site_title',$data->alia_name)
                ->with('is_has',$this->isHasZhuanlan());
        }else{
            return view('errors.404');
        }
    }
    /**
     * 保存
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        $data = [];
        $data['name'] = $request->input('name');
        $data['alia_name'] = $request->input('alia_name');
        $data['topic'] = $request->input('theme') ?$request->input('theme') :'古诗赏析';
        $data['about'] = $request->input('bio');
        $data['specialty'] = $request->input('specialty');
        $data['avatar'] = $request->input('logo_url');
        $data['wechat'] = $request->input('wechat');
        $data['name'] = $request->input('name');
        $data['creator_id'] = Auth::user()->id;
        $data['created_at'] = date('Y-m-d H:i:s',time());
        $data['status'] = 'active';
        $zhuanlan_id = DB::table('dev_zhuanlan')->insertGetId($data);
        $res = [];
        if(isset($zhuanlan_id) && $zhuanlan_id){
            $res['message'] = 'success';
            $res['id'] = $zhuanlan_id;
            return response()->json($res);
        }else{
            $res['message'] = 'fail';
            $res['id'] = $zhuanlan_id;
            return response()->json($res);
        }
    }

    /**
     * 判断当前输入域名是否已存在
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function judgeDomain(Request $request){
        $name = $request->input('domain');
        $data = DB::table('dev_zhuanlan')->where('name','=',$name)->first();
        $_res = [];
        if(isset($data) && $data){
            $_res['status'] = 'fail';
        }else{
            $_res['status'] = 'success';
        }
        return response()->json($_res);
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
     * 关注专栏
     * @param $domain
     * @return mixed
     */
    public function follow($domain){
        $res = null;
        $_data = [];
        // 通过name获取id
        $zl = DB::table('dev_zhuanlan')->where('name','=',$domain)->first();
        $zl_id = $zl->id;
        // 检测是否有记录
        $_follow = DB::table('dev_zl_follow')
            ->where('u_id',Auth::user()->id)
            ->where('zl_id',$zl_id)
            ->first();
        if(isset($_follow) && $_follow){
            // 是
            $status = 0;
            if($_follow->status <1){
                // 已关注
                $status = 1;
            }
            $res = DB::table('dev_zl_follow')
                ->where('u_id',Auth::user()->id)
                ->where('zl_id',$zl_id)
                ->update([
                    'status'=>$status,
                    'updated_at' => date('Y-m-d H:i:s',time())
                ]);
        }else{
            // 否
            $data = [];
            $data['u_id'] = Auth::user()->id;
            $data['zl_id'] = $zl_id;
            $data['created_at'] = date('Y-m-d H:i:s',time());
            $data['updated_at'] = date('Y-m-d H:i:s',time());
            $data['status'] = 1;
            $res = DB::table('dev_zl_follow')->insert($data);
        }
        if($res){
            $_data['status'] = 'success';
        }else{
            $_data['status'] = 'fail';
        }
        $_data['count'] = $this->getZLFollowCount($zl_id);
        return response()->json($_data);
    }
    public function followers($domain){
        // 通过name获取id
        $zl = DB::table('dev_zhuanlan')->where('name','=',$domain)->first();

        if(isset($zl) && $zl){
            $zl_id = $zl->id;
            $_followers = DB::table('dev_zl_follow')
                ->where('dev_zl_follow.zl_id',$zl_id)
                ->where('dev_zl_follow.status',1)
                ->leftJoin('users','users.id','=','dev_zl_follow.u_id')
                ->select('dev_zl_follow.u_id','users.*')
                ->get();
            return view('zhuan.zhuanlan.followers')
                ->with('site_title','关注者')
                ->with('count',$this->getZLFollowCount($zl_id))
                ->with('followers',$_followers);
        }else{
            return view('errors.404');
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
     * 判断是否关注
     * @param $id
     * @return bool
     */
    static function is_follow($id){
        if (Auth::guest()){
            return false;
        }
        $_follow = DB::table('dev_zl_follow')
            ->where('u_id',Auth::user()->id)
            ->where('zl_id',$id)
            ->where('status',1)
            ->first();
        if(isset($_follow) && $_follow){
            return true;
        }else{
            return false;
        }
    }
}