<?php
/**
 * Controller review
 * 专栏 评论 controller
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
class ReviewController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * 创建评论
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function create(Request $request,$id=null)
    {
        // $id post_id
        $data = array();
        $msg = null;
        $_data = null;
        if ($id && !Auth::guest()) {
            $_data = DB::table('dev_review')->insertGetId([
                't_id' => $id,
                't_type' => 'post',
                'parent_id' => $request->input('parent_id'),
                'content' => $request->input('content'),
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
                'u_id' => Auth::user()->id,
                'status' => 'active'
            ]);
            if ($_data && $_data > 0) {
                $data['status'] = 'success';
                $data['msg'] = '评论提交成功';
            } else {
                $data['msg'] = '评论提交失败';
            }
        } else {
            $data['msg'] = '需要先登录哦！';
        }
        return response()->json($data);
    }

    /**
     * 获取评论
     * @param $id
     * @return $this
     */
    public function show($id){
        $user_id = -1;
        if (!Auth::guest()){
            $user_id = Auth::user()->id;
        }
        $comments = DB::table('dev_review')
            ->where('dev_review.t_id',$id)
            ->where('dev_review.status','active')
            ->leftJoin('users','users.id','=','dev_review.u_id')
            ->select('dev_review.*','users.name','users.avatar','users.domain')
            ->orderBy('dev_review.created_at','desc')
            ->paginate(4);
        $comments->setPath('/api/posts/'.$id.'/comments');
        foreach ($comments as $key=>$comment){
            $_comment = DB::table('dev_review')
                ->where('dev_review.id',$comment->parent_id)
                ->where('dev_review.status','active')
                ->leftJoin('users','users.id','=','dev_review.u_id')
                ->select('dev_review.*','users.name','users.avatar','users.domain')
                ->orderBy('dev_review.created_at','desc')
                ->first();
            if($_comment){
                $comments[$key]->p_u_id = $_comment->u_id;
                $comments[$key]->p_name = $_comment->name;
                $comments[$key]->p_domain = $_comment->domain;
            }
            $comments[$key]->is_like = $this->is_like($comment->id);
            $comments[$key]->like_count = $this->getLikeCount($comment->id);
        }
        return view('zhuan.partials.comments')
            ->with('comments',$comments)
            ->with('user_id',$user_id)
            ->with('type','comments');
    }

    /**
     * 获取评论的对话信息
     * @param $id
     * @param $parent_id
     * @return mixed
     */
    public function conversation($id,$parent_id){
        // id 为文章id
        // parent_id为评论的parent_id
        $user_id = -1;
        if (!Auth::guest()){
            $user_id = Auth::user()->id;
        }
        $comments = array();
        $comment = DB::table('dev_review')
            ->where('t_id',$id)
            ->where('dev_review.id',$parent_id)
            ->where('dev_review.status','active')
            ->leftJoin('users','users.id','=','dev_review.u_id')
            ->select('dev_review.*','users.name','users.avatar','users.domain')
            ->orderBy('dev_review.created_at','desc')
            ->first();
        $comment->is_like = $this->is_like($parent_id);
        $comment->like_count = $this->getLikeCount($parent_id);
        array_push($comments,$comment);
        $_comments = DB::table('dev_review')
            ->where('t_id',$id)
            ->where('dev_review.parent_id',$parent_id)
            ->where('dev_review.status','active')
            ->leftJoin('users','users.id','=','dev_review.u_id')
            ->select('dev_review.*','users.name','users.avatar','users.domain')
            ->orderBy('dev_review.created_at','desc')
            ->get();
        foreach ($_comments as $key=>$_comment){
            if($_comments){
                $_comments[$key]->p_u_id = $_comment->u_id;
                $_comments[$key]->p_name = $_comment->name;
                $_comments[$key]->p_domain = $_comment->domain;
                $_comments[$key]->is_like = $this->is_like($_comment->id);
                $_comments[$key]->like_count = $this->getLikeCount($_comment->id);
            }
            array_push($comments,$_comments[$key]);
        }

        return view('zhuan.partials.comments')
            ->with('comments',$comments)
            ->with('user_id',$user_id)
            ->with('type','conversation');
    }
    /**
     * 更新点赞状态
     * @param $id
     * @param $t_id
     * @return mixed
     */
    public function like($id,$t_id){
        // id 为文章id
        // t_id为评论id
        $data = array();
        $msg = null;
        $_data = null;
        if ($t_id && !Auth::guest()) {
            $_data = DB::table('dev_like')
                ->where('type','post_review')
                ->where('like_id',$t_id)
                ->where('user_id',Auth::user()->id)
                ->first();
            if (isset($_data) && $_data)  {
                // 已存在记录
                if($_data->status == 'active'){
                    DB::table('dev_like')
                        ->where('type','post_review')
                        ->where('like_id',$t_id)
                        ->where('user_id',Auth::user()->id)
                        ->update([
                            'status' => 'delete',
                            'updated_at' =>date('Y-m-d H:i:s', time())
                        ]);
                    $data['msg'] = '赞 -1';
                    $data['status'] = 'delete';
                }else{
                    DB::table('dev_like')
                        ->where('type','post_review')
                        ->where('like_id',$t_id)
                        ->where('user_id',Auth::user()->id)
                        ->update([
                            'status' => 'active',
                            'updated_at' =>date('Y-m-d H:i:s', time())
                        ]);
                    $data['msg'] = '赞 +1';
                    $data['status'] = 'active';
                }
            } else {
                // 新纪录
                DB::table('dev_like')
                    ->insert([
                        'user_id'=>Auth::user()->id,
                        'like_id'=>$t_id,
                        'type'=> 'post_review',
                        'status' => 'active',
                        'created_at' =>date('Y-m-d H:i:s', time()),
                        'updated_at' =>date('Y-m-d H:i:s', time())
                    ]);
                $data['msg'] = '赞 +1';
                $data['status'] = 'active';
            }
            $data['count'] = $this->getLikeCount($t_id);
        } else {
            $data['msg'] = '需要先登录哦！';
        }
        return response()->json($data);
    }

    /**
     * 删除评论
     * @param $id
     * @param $t_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id,$t_id){
        $res = DB::table('dev_review')
            ->where('id',$t_id)
            ->where('t_id',$id)
            ->update([
                'status' => 'delete',
                'updated_at' => date('Y-m-d H:i:s', time())
            ]);
        $_data = [];
        if($res){
            $_data['status'] = 'success';
            $_data['msg'] = '删除成功';
            return response()->json($_data);
        }else{
            $_data['status'] = 'fail';
            $_data['msg'] = '删除失败';
            return response()->json($_data);
        }
    }
}