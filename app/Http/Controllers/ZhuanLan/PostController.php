<?php
/**
 * Controller show
 * 专栏 文章 controller
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
class PostController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * 写文章
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function write(){
        if (Auth::guest()){
            return redirect('/login');
        }
        return view('zhuan.post.create')
            ->with('is_has',$this->isHasZhuanlan())
            ->with('site_title','写文章');
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