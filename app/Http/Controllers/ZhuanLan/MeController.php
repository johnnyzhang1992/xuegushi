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
    public function drafts(){
        $posts = DB::table('dev_post')
            ->where('creator_id',Auth::user()->id)
            ->where('status','draft')
            ->orderBy('id','desc')
            ->get();
        return view('zhuan.post.draft')
            ->with('is_has',$this->isHasZhuanlan())
            ->with('site_title','我的草稿')
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
}