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
}