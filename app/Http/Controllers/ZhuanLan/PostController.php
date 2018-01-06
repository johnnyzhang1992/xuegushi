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
     * c创建文章
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        $data = [];
        $data['creator_id'] = Auth::user()->id;
        $data['created_at'] = date('Y-m-d H:i:s',time());
        $data['updated_at'] = date('Y-m-d H:i:s',time());
        $data['zhuanlan_id'] = $this->getUserZhuanId(Auth::user()->id);
        $data['title'] = $request->input('title');
        $data['topic']=$request->input('topic');
        $data['content']=$request->input('content');
        $data['cover_url']=$request->input('cover_image');
        $data['status'] = $request->input('status');
        $_id = DB::table('dev_post')->insertGetId($data);
        $res = [];
        if($_id){
            $res['id'] = $_id;
            $res['status'] = 'success';
        }
        return response()->json($res);
    }

    /**
     * update 文章
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request){
        $id = $request->input('id');
        $data = [];
        $data['updated_at'] = date('Y-m-d H:i:s',time());
        $data['title'] = $request->input('title');
        $data['topic']=$request->input('topic');
        $data['content']=$request->input('content');
        $data['cover_url']=$request->input('cover_image');
        $data['status'] = $request->input('status');
        $_res = DB::table('dev_post')->where('id',$id)->update($data);
        $res = [];
        if($_res){
            $res['status'] = 'success';
        }
        return response()->json($res);
    }
    public function show($id){
        $data = DB::table('dev_post')
            ->where('dev_post.id',$id)
            ->leftJoin('users','users.id','=','dev_post.creator_id')
            ->leftJoin('dev_zhuanlan','dev_zhuanlan.id','=','dev_post.zhuanlan_id')
            ->select('dev_post.*','users.name as user_name','users.avatar','dev_zhuanlan.alia_name as zhuan_alia_name',
                'dev_zhuanlan.specialty','dev_zhuanlan.avatar as zhuan_avatar','dev_zhuanlan.name as zhuan_name')
            ->first();
        if(isset($data) && $data){
            return view('zhuan.post.show')
                ->with('post',$data)
                ->with('is_has',$this->isHasZhuanlan())
                ->with('site_title',$data->title);
        }else{
            return view('errors.404')->with('record_id',$id)->with('record_name','文章');
        }
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
     * 返回用户的专栏id
     * @param $id
     * @return mixed
     */
    public function getUserZhuanId($id){
        $data = DB::table('dev_zhuanlan')->where('creator_id',$id)->first();
        if(isset($data) && $data){
            return $data->id;
        }else{
            return -1;
        }
    }
}