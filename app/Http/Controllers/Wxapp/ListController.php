<?php

namespace App\Http\Controllers\Wxapp;

use App\Http\Controllers\Controller;
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


class ListController extends Controller {

    public function __construct () {
        parent::__construct();
    }

    /**
     * 获取诗单列表
     * @param Request $request
     * @return Response
     */
    public function getLists(Request $request){
        $user_id = $request->input('user_id');
        $wx_token = $request->input('wx_token');
        $is_validate = $this->validateWxUser($user_id,$wx_token);
        if($is_validate){
            $lists = DB::table('dev_list')
            ->where('user_id',$user_id)
            ->where('status','active')
            ->orderBy('id','desc')
            ->paginate(10);
            $data['status'] = 200;
            $data['data'] = $lists;
            return response()->json($data);
        }else{
            $data['msg'] = '操作不合法';
            $data['status'] = 500;
            return response()->json($data);
        }
    }

    /**
     * 获取某个诗单详情
     * @param Request $request
     * @return Response
     */
    public function detail($id){
        $list = DB::table('dev_list')->where('id',$id)->first();
        $data = [];
        if(isset($list) && $list){
            $list_items = DB::table('dev_list_item')
            ->where('list_id',$id)
            ->whereNull('deleted_at')
            ->paginate(10);
            $data['status'] = 200;
            $data['list'] = $list;
            $data['items'] = $list_items;
        }else{
            $data['status'] = 500;
            $data['msg'] = '诗单不存在';
        }
        return response()->json($data);
    }

    /**
     * 创建诗单
     * @param Request $request
     * @return Response
     */
    public function create(Request $request){
        $user_id = $request->input('user_id');
        $wx_token = $request->input('wx_token');
        $title = $request->input('title');
        $cover = $request->input('cover');
        $secret = $request->input('secret');
        $is_secret = isset($secret) && $secret > 0 ? true : false;
        $is_validate = $this->validateWxUser($user_id,$wx_token);
        if($is_validate){
            if(!isset($title) || $title ===''){
                return response()->json([
                    'status' => 500,
                    'message' => 'title 不能为空'
                ]);
            }
            $list_id = DB::table('dev_list')
                ->insertGetId([
                    'created_at' => date('Y-m-d H:i:s',time()),
                    'updated_at' => date('Y-m-d H:i:s',time()),
                    'title' => $title,
                    'cover' => $cover ? $cover : null,
                    'user_id' => $user_id,
                    'secret' => $is_secret
                ]);
            $data['status'] = 200;
            $data['list_id'] = $list_id;
            return response()->json($data);
        }else{
            $data['msg'] = '操作不合法';
            $data['status'] = 500;
            return response()->json($data);
        }
    }

    /**
     * 更新诗单内容
     */
    public function update(Request $request){
        $user_id = $request->input('user_id');
        $wx_token = $request->input('wx_token');
        $list_id = $request->input('list_id');
        $title = $request->input('title');
        $cover = $request->input('cover');
        $secret = $request->input('secret');
        $is_secret = isset($secret) && $secret > 0 ? true : false;
        $data = [];
        $is_validate = $this->validateWxUser($user_id,$wx_token);
        if($is_validate){
            if(!isset($title) || $title ===''){
                return response()->json([
                    'status' => 500,
                    'message' => 'title 不能为空'
                ]);
            }
            $list = DB::table('dev_list')->where('id',$list_id)->first();
            $collectCount = DB::table('dev_collect')
                            ->where('type','list')
                            ->where('user_id',$user_id)
                            ->where('like_id',$list_id)
                            ->count();
            if($collectCount>0){
                $data['status'] = 401;
                $data['msg'] = '诗单已有'+$collectCount+'收藏，不可设为隐私了哦！';
                return response()->json($data);
            }
            if(isset($list) && $list){
                $is_update = DB::table('dev_list')
                ->where('id',$list_id)
                ->where('user_id',$user_id)
                ->update([
                    'updated_at' => date('Y-m-d H:i:s',time()),
                    'title' => $title,
                    'cover' => $cover ? $cover : null,
                    'secret' => $is_secret
                ]);
                if(isset($is_update) && $is_update){
                    $data['msg'] = '更新成功！';
                    $data['status'] = 200;
                }else{
                    $data['msg'] = '更新失败！';
                    $data['status'] = 500;
                }
            }else{
                $data['status'] = 500;
                $data['msg'] = "诗单不存在";
            }
            $data['list_id'] = $list_id;
            return response()->json($data);
        }else{
            $data['msg'] = '操作不合法';
            $data['status'] = 500;
            return response()->json($data);
        }
    }

    /**
     * 删除诗单
     */
     public function delete(Request $request){
        $user_id = $request->input('user_id');
        $wx_token = $request->input('wx_token');
        $list_id = $request->input('list_id');
        $data = [];
        $is_validate = $this->validateWxUser($user_id,$wx_token);
        if($is_validate){
            $list = DB::table('dev_list')->where('id',$list_id)->first();
            $collectCount = DB::table('dev_collect')
                            ->where('type','list')
                            ->where('user_id',$user_id)
                            ->where('like_id',$list_id)
                            ->count();
            if($collectCount>0){
                $data['status'] = 401;
                $data['msg'] = '诗单已有'+$collectCount+'收藏，不可删除哦！';
                return response()->json($data);
            }
            if(isset($list) && $list){
                $is_update = DB::table('dev_list')
                ->where('id',$list_id)
                ->where('user_id',$user_id)
                ->update([
                    'status' => 'delete'
                ]);
                if(isset($is_update) && $is_update){
                    $data['msg'] = '删除成功！';
                    $data['status'] = 200;
                }
            }else{
                $data['status'] = 500;
                $data['msg'] = "诗单不存在";
            }
            $data['list_id'] = $list_id;
            return response()->json($data);
        }else{
            $data['msg'] = '操作不合法';
            $data['status'] = 500;
            return response()->json($data);
        }
     }

     /**
      * 诗单子项目添加
      * @param Request $request
      * @return Response
      */
      public function itemCreate(Request $request){
        $user_id = $request->input('user_id');
        $wx_token = $request->input('wx_token');
        $type = $request->input('type'); // poem,sentence.poet
        $target_id = $request->input('target_id');
        $list_id = $request->input('list_id');
        $data = [];
        // 验证用户信息
        if($this->validateWxUser($user_id,$wx_token)){
            // 查看诗单是否存在
            $list = DB::table('dev_list')->where('id',$list_id)->first();
            if(isset($list) && $list){
                if(isset($type) && isset($target_id)){
                    $list_item = DB::table('dev_list_item')
                    ->where('list_id',$list_id)
                    ->where('target_id',$target_id)
                    ->first();
                    // 记录已存在，直接更新
                    if($list_item){
                        $is_update = DB::table('dev_list_item')
                        ->where('list_id',$list_id)
                        ->where('target_id',$target_id)
                        ->update([
                            'deleted_at' => null,
                        ]);
                        if(isset($is_update) && $is_update){
                            $data['item_id'] = $list_item->id;
                            $data['status'] = 200;
                        }else{
                            $data['msg'] = '保存失败！';
                            $data['status'] = 500;
                        }
                    }else{
                        $list_item_id = DB::table('dev_list_item')
                        ->insertGetId([
                            'type' => $type,
                            'target_id' => $target_id,
                            'list_id' => $list_id,
                            'created_at' => date('Y-m-d H:i:s',time()),
                        ]);
                        $data['item_id'] = $list_item_id;
                    }
                }else{
                    $data['status'] = 500;
                    $data['msg'] = '参数缺少，type或者target_id';
                }
            }else{
                $data['status'] = 500;
                $data['msg'] = '诗单不存在！';
            }
        }
        return response()->json($data);
      }

     /**
      * 诗单子项目更新（编辑或者删除
      * @param Request $request
      * @return Response
      */
    public function itemDelete(Request $request){
        $user_id = $request->input('user_id');
        $wx_token = $request->input('wx_token');
        $item_id = $request->input('item_id');
        $list_id = $request->input('list_id');
        $data = [];
        // 验证用户信息
        if($this->validateWxUser($user_id,$wx_token)){
            // 查看诗单是否存在
            $list_item = DB::table('dev_list_item')
                ->where('list_id',$list_id)
                ->where('id',$item_id)
                ->first();
            if(isset($list_item) && $list_item){
                $is_update = DB::table('dev_list_item')
                ->where('list_id',$list_id)
                ->where('id',$item_id)
                ->update([
                    'deleted_at' => date('Y-m-d H:i:s',time()),
                ]);
                if(isset($is_update) && $is_update){
                    $data['msg'] = '更新成功！';
                    $data['status'] = 200;
                }else{
                    $data['msg'] = '更新失败！';
                    $data['status'] = 500;
                }
            }else{
                $data['status'] = 500;
                $data['msg'] = '记录不存在！';
            }
        }
        return response()->json($data);
    }
}