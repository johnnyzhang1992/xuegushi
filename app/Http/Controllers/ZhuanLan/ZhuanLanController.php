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
        $zhuanlans = DB::table('dev_zhuanlan')->paginate(8);
        foreach ($zhuanlans as $key=>$zhuan){
            $zhuanlans[$key]->post_count = DB::table('dev_post')->where('zhuanlan_id',$zhuan->id)->count();
        }
        $posts = DB::table('dev_post')
            ->where('dev_post.status','active')
            ->leftJoin('users','users.id','=','dev_post.creator_id')
            ->select('dev_post.*','users.name as author_name')
            ->orderBy('dev_post.pv_count','asc')->paginate(6);
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
                ->orderBy('dev_post.pv_count','asc')->paginate(6);
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
}