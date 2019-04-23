<?php

namespace App\Http\Controllers\Wxapp;

use App\Http\Controllers\Controller;
use http\Env\Response;
use Illuminate\Http\Request;

use Cache;
use Carbon;
use DB;
use Log;
use Hash;
use Auth;
use Config;
use \stdClass;
use App\Helpers\DateUtil;
//use phpDocumentor\Reflection\Types\Array_;


class TopicController extends Controller
{
    /**
     * 验证微信token的有效性
     * @param $token
     * @param $u_id
     * @return boolean
     */
    public function validateWxToken($u_id, $token)
    {
        if(isset($u_id)){
            $user = DB::table('users')
                ->where('id', $u_id)
                ->where('wx_token', trim($token))
                ->first();
            if (isset($user) && $user) {
                // 验证通过
                return true;
            }
        }
        return false;
    }

    /**
     * 获取最新的一条话题数据
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecentTopic(){
        $topic = DB::table('dev_wx_topic')
            ->where('status','active')
            ->orderBy('id','desc')
            ->first();
        return \response()->json($topic);
    }

    /**
     * 获取话题详情
     * @param $id
     * @return mixed
     */
    public function getTopicDetail($id){
        $topic = DB::table('dev_wx_topic')
            ->where('status','active')
            ->where('id',$id)
            ->orderBy('id','desc')
            ->first();
        return response()->json($topic);
    }
    /**
     * 获取话题列表，方便管理员管理
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTopics(Request $request){
        $user_id = $request->input('user_id');
        $wx_token = $request->input('wx_token');
        $data = [];
//        if (!$this->validateWxToken($user_id, $wx_token)) {
//            $data['msg'] = '操作不合法';
//            $data['status'] = 500;
//            return response()->json($data);
//        }
        $topics = DB::table('dev_wx_topic')
            ->where('status','active')
            ->orderBy('id','desc')
            ->paginate(4);
        return response()->json($topics);
    }
    /**
     * 创建话题
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTopic(Request $request)
    {
        $user_id = $request->input('user_id');
        $wx_token = $request->input('wx_token');
        $name = $request->input('name');
        $description = $request->input('desc');
        $data = [];
        if (!$this->validateWxToken($user_id, $wx_token)) {
            $data['msg'] = '操作不合法';
            $data['status'] = 500;
            return response()->json($data);
        }
        $topic_id = DB::table('dev_wx_topic')->insertGetId([
            'created_at' => date('Y-m-d H:i:s',time()),
            'updated_at' => date('Y-m-d H:i:s',time()),
            'name' => $name,
            'desc' => $description,
            'status' => 'active'
        ]);
        if($topic_id){
            $data['msg'] = '创建成功';
            $data['status'] = 200;
        }else{
            $data['msg'] = '创建失败';
            $data['status'] = 500;
        }
        return response()->json($data);
    }

    /**
     * 删除 topic
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTopic(Request $request,$id){
        $user_id = $request->input('user_id');
        $wx_token = $request->input('wx_token');
//        $id = $request->input('id');
        $data = [];
        // 判断是否是管理员
        if (!$this->validateWxToken($user_id, $wx_token)) {
            $data['msg'] = '操作不合法';
            $data['status'] = 500;
            return response()->json($data);
        }
        // 先判断话题是否存在
        $topic = DB::table('dev_wx_topic')->where('id',$id)->where('status','active')->first();
        if(isset($topic) && $topic){
            // 存在，改变状态
            $res = DB::table('dev_wx_topic')->where('id',$id)->update([
                'updated_at' => date('Y-m-d H:i:s',time()),
                'status' => 'delete'
            ]);
            if($res){
                $data['msg'] = '删除成功';
                $data['status'] = 200;
            }else{
                $data['msg'] = '删除成功';
                $data['status'] = 500;
            }
        }else{
            // 不存在，报错
            $data['msg'] = '删除失败';
            $data['status'] = 500;
        }
        return response()->json($data);
    }
}