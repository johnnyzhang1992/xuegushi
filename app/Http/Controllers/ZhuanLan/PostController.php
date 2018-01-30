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

    public function index(){
        $posts = DB::table('dev_post')
            ->where('dev_post.status','active')
            ->leftJoin('users','users.id','=','dev_post.creator_id')
            ->select('dev_post.*','users.name as author_name')
            ->orderBy('dev_post.created_at','desc')
            ->orderBy('dev_post.pv_count','desc')
            ->paginate(9);
        return view('zhuan.post.index')
            ->with('query','home')
            ->with('posts',$posts)
            ->with('is_has',$this->isHasZhuanlan());
    }
    /**
     * 写文章
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function write(){
        if (Auth::guest()){
            return redirect('/login');
        }
        $zhuanlans = DB::table('dev_zhuanlan')
            ->where('creator_id',Auth::user()->id)
            ->get();
        return view('zhuan.post.create')
            ->with('is_has',$this->isHasZhuanlan())
            ->with('zhuans',$zhuanlans)
            ->with('site_title','写文章');
    }

    /**
     * c创建文章
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        $zl_id = $request->input('zhuanlan');
        $zhuanlan_id = $zl_id == -1 ? $this->getUserZhuanId(Auth::user()->id) : $zl_id;
        $data = [];
        $data['creator_id'] = Auth::user()->id;
        $data['created_at'] = date('Y-m-d H:i:s',time());
        $data['updated_at'] = date('Y-m-d H:i:s',time());
        $data['zhuanlan_id'] = $zhuanlan_id;
        $data['title'] = $request->input('title');
        $data['topic']=$request->input('topic');
        $data['content']=$request->input('content');
        $data['cover_url']=$request->input('cover_image');
        $data['status'] = $request->input('status');
        $_id = DB::table('dev_post')->insertGetId($data);
        $res = [];
        if($_id){
            $_data = [];
            $_data['zhuanlan_id'] = $zhuanlan_id;
            $_data['post_id'] = $_id;
            $_data['creator_id'] = Auth::user()->id;
            $_data['created_at'] = date('Y-m-d H:i:s',time());
            DB::table('dev_post_relation')->insertGetId($_data);
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
        $zl_id = $request->input('zhuanlan');
        $zhuanlan_id = $zl_id == -1 ? $this->getUserZhuanId(Auth::user()->id) : $zl_id;
        $id = $request->input('id');
        $data = [];
        $data['updated_at'] = date('Y-m-d H:i:s',time());
        $data['title'] = $request->input('title');
        $data['topic']=$request->input('topic');
        $data['content']=$request->input('content');
        $data['cover_url']=$request->input('cover_image');
        $data['status'] = $request->input('status');
        $data['zhuanlan_id'] = $zhuanlan_id;
        $_res = DB::table('dev_post')->where('id',$id)->update($data);
        $res = [];
        if($_res){
            $res['status'] = 'success';
        }
        return response()->json($res);
    }

    /**
     * 删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request){
        $id = $request->input('id');
        $_res = DB::table('dev_post')->where('id',$id)->update([
            'status' => 'delete',
            'updated_at' => date('Y-m-d H:i:s',time())
        ]);
        $res = [];
        if($_res){
            $res['status'] = 'success';
        }
        return response()->json($res);
    }

    /**
     * 恢复
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(Request $request){
        $id = $request->input('id');
        $_res = DB::table('dev_post')->where('id',$id)->update([
            'status' => 'active',
            'updated_at' => date('Y-m-d H:i:s',time())
        ]);
        $res = [];
        if($_res){
            $res['status'] = 'success';
        }
        return response()->json($res);
    }
    /**
     * 详情页
     * @param $id
     * @return $this
     */
    public function show($id){
        $user_id = null;
        $data = DB::table('dev_post')
            ->where('dev_post.id',$id)
            ->leftJoin('users','users.id','=','dev_post.creator_id')
            ->leftJoin('dev_zhuanlan','dev_zhuanlan.id','=','dev_post.zhuanlan_id')
            ->select('dev_post.*','users.name as user_name','users.avatar','dev_zhuanlan.alia_name as zhuan_alia_name',
                'dev_zhuanlan.about','dev_zhuanlan.avatar as zhuan_avatar','dev_zhuanlan.name as zhuan_name')
            ->first();
        if (!Auth::guest()){
          $user_id = Auth::user()->id;
        }
        if(isset($data) && $data){
            $comments = DB::table('dev_review')
                ->where('dev_review.t_id',$id)
                ->leftJoin('users','users.id','=','dev_review.u_id')
                ->select('dev_review.*','users.name','users.avatar','users.domain')
                ->orderBy('dev_review.like_count','desc')
                ->orderBy('dev_review.created_at','asc')
                ->paginate(9);
            $comments->setPath('api/posts/'.$id.'/comments');
            foreach ($comments as $key=>$comment){
                $_comment = DB::table('dev_review')
                    ->where('dev_review.id',$comment->parent_id)
                    ->leftJoin('users','users.id','=','dev_review.u_id')
                    ->select('dev_review.*','users.name','users.avatar','users.domain')
                    ->orderBy('dev_review.like_count','desc')
                    ->orderBy('dev_review.created_at','asc')
                    ->first();
                if($_comment){
                    $comments[$key]->p_u_id = $_comment->u_id;
                    $comments[$key]->p_name = $_comment->name;
                    $comments[$key]->p_domain = $_comment->domain;
                }
            }
            DB::table('dev_post')->where('id',$data->id)->increment("pv_count");
            return view('zhuan.post.show')
                ->with('post',$data)
                ->with('is_like',$this->judgeLikeOrCollect('dev_like',$id,$user_id))
                ->with('is_collect',$this->judgeLikeOrCollect('dev_collect',$id,$user_id))
                ->with('is_has',$this->isHasZhuanlan())
                ->with('comments',$comments)
                ->with('site_title',$data->title);
        }else{
            return view('errors.404')->with('record_id',$id)->with('record_name','文章');
        }
    }
    /**
     * 编辑
     * @param $id
     * @return mixed
     */
    public function edit($id=null){
        if (Auth::guest()){
            return redirect('/login');
        }
        $data = DB::table('dev_post')
            ->where('dev_post.id',$id)
            ->first();
        if(isset($data) && $data){
            if($data->creator_id == Auth::user()->id){
                $zhuanlans = DB::table('dev_zhuanlan')
                    ->where('creator_id',Auth::user()->id)
                    ->get();
                return view('zhuan.post.edit')
                    ->with('post',$data)
                    ->with('zhuans',$zhuanlans)
                    ->with('is_has',$this->isHasZhuanlan())
                    ->with('site_title','编辑-'.$data->title);
            }else{
                return view('errors.403');
            }

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
    /**
     * 更新 post 的like_count
     * @param $id
     * @param $type
     * @return mixed
     */
    public function updateLikeOrCollect($id,$type){
        $_table_name = 'dev_like';
        if(isset($type) && $type && $type== 'collect'){
            $_table_name = 'dev_collect';
        }
        $data = array();
        $msg = null;
        $res = false;
        $_data = null;
        if($id  && !Auth::guest()){
            // 先判断是否存在
            $_res = DB::table($_table_name)
                ->where('user_id',Auth::user()->id)
                ->where('like_id',$id)
                ->where('type','post')
                ->first();
            if(!$_res){
                // 新的like
                if($type =='collect'){
                    $res = DB::table('dev_post')->where('id',$id)->increment("collect_count");
                }else{
                    $res = DB::table('dev_post')->where('id',$id)->increment("like_count");
                }
                $_data = DB::table('dev_post')->where('id',$id)->first();
                DB::table($_table_name)->insertGetId(
                    [
                        'like_id' => $id,
                        'type' => 'post',
                        'created_at' => date('Y-m-d H:i:s',time()),
                        'updated_at' => date('Y-m-d H:i:s',time()),
                        'user_id'=> Auth::user()->id,
                        'status' => 'active'
                    ]
                );

                $msg = '喜欢+1';
                if($type =='collect'){
                    $msg = '收藏+1';
                }
                $data['status'] = 'active';
            }else{
                // 更新like状态
                if($_res->status == 'active'){
                    if($type =='collect'){
                        $res = DB::table('dev_post')->where('id',$id)->decrement("collect_count");
                    }else{
                        $res = DB::table('dev_post')->where('id',$id)->decrement("like_count");
                    }
                    $_data = DB::table('dev_post')->where('id',$id)->first();
                    DB::table($_table_name)
                        ->where('user_id',Auth::user()->id)
                        ->where('id',$_res->id)
                        ->update([
                            'status' => 'delete',
                            'updated_at' => date('Y-m-d H:i:s',time())
                        ]);
                    $msg = '取消喜欢成功';
                    if($type =='collect'){
                        $msg = '取消收藏成功';
                    }
                    $data['status'] = 'delete';
                }else{
                    if($type =='collect'){
                        $res = DB::table('dev_post')->where('id',$id)->increment("collect_count");
                    }else{
                        $res = DB::table('dev_post')->where('id',$id)->increment("like_count");
                    }
                    $_data = DB::table('dev_post')->where('id',$id)->first();
                    DB::table($_table_name)
                        ->where('user_id',Auth::user()->id)
                        ->where('id',$_res->id)
                        ->update([
                            'status' => 'active',
                            'updated_at' => date('Y-m-d H:i:s',time()),
                        ]);
                    $msg = '喜欢+1';
                    if($type =='collect'){
                        $msg = '收藏+1';
                    }
                    $data['status'] = 'active';
                }
            }
        }else{
            $msg = '需要先登录哦！';
        }
        if($res){
            $data['msg'] = $msg;
            $data['num'] = $_data->like_count;
            if($type =='collect'){
                $data['num'] = $_data->collect_count;
            }
            return response()->json($data);
        }else{
            $data['msg'] = $msg;
            $data['status'] = 'fail';
            return response()->json($data);
        }
    }
    /**
     * 判断是否like
     * @param $table_name
     * @param $id
     * @param $user_id
     * @return boolean
     */
    public function judgeLikeOrCollect($table_name,$id,$user_id){
        $status = false ;
        if($id && $user_id){
            $_res = DB::table($table_name)
                ->where('like_id',$id)
                ->where('user_id',$user_id)
                ->where('status','active')
                ->where('type','post')
                ->first();
            if($_res){
                $status = true;
            }
        }
        return $status;
    }
}