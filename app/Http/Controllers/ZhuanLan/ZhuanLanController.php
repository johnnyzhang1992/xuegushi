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
class ZhuanLanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        return view('zhuan.index')
            ->with('query','home');
    }

    public function apply(){
        return view('zhuan.zhuanlan.create');
    }
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
}