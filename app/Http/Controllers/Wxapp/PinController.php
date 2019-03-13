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
//use phpDocumentor\Reflection\Types\Array_;


class PinController extends Controller {
    /**
     * 验证微信token的有效性
     * @param $token
     * @param $u_id
     * @return boolean
     */
    public function validateWxToken($u_id,$token){
        $user = DB::table('users')
            ->where('id',$u_id)
            ->where('wx_token',trim($token))
            ->first();
        if(isset($user) && $user){
            // 验证通过
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取轮播图
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSliderImages(){
        $images = [
            'https://img.starimg.cn/gushi/slider_001.png',
            'http://img06.tooopen.com/images/20160818/tooopen_sy_175866434296.jpg',
            'http://img06.tooopen.com/images/20160818/tooopen_sy_175833047715.jpg'
        ];

        return response()->json($images);
    }

    /**
     * 创建想法
     * @param Request $request
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPin(Request $request,$user_id){
        $p_id = $request->input('p_id');
        $t_type = $request->input('t_type');
        $content = $request->input('content');
        $t_id = $request->input('t_id');
        $location = $request->input('location');
        $wx_token = $request->input('wx_token');
        if(!$this->validateWxToken($user_id,$wx_token)){
            $data['pin_id'] = 0;
            $data['msg'] = '操作不合法';
            $data['status'] = 200;
            return response()->json($data);
        }
        if($p_id >0){
            $p_pin = DB::table('dev_pin')
                ->leftJoin('dev_wx_users','dev_wx_users.user_id','=','dev_pin.u_id')
                ->where('dev_pin.id','=',$p_id)
                ->select('dev_pin.*','dev_wx_users.name')
                ->first();
            if(isset($p_pin) && $p_pin){
                $content = $content.' @'.$p_pin->name.': '.$p_pin->content;
            }
        }
        $data = [];
        if($t_id>0){

            $pin_id = DB::table('dev_pin')->insertGetId([
                't_id' => $t_id,
                't_type'=>isset($t_type) && $t_type !='' ? $t_type : 'pin',
                'created_at' => date('Y-m-d H:i:s',time()),
                'updated_at' => date('Y-m-d H:i:s',time()),
                'u_id'=> $user_id,
                'status' => 'active',
                'like_count' =>0,
                'p_id'=>isset($p_id) && $p_id ? $p_id : 0 ,
                'content' => $content,
                'location'=>$location,
                'flag' => 99
            ]);
            if(isset($pin_id) && $pin_id){
                $data['pin_id'] = $pin_id;
                $data['status'] = 200;
            }else{
                $data['pin_id'] = 0;
                $data['status'] = 200;
                $data['msg'] = 'fail';
            }
        }else{
            if(trim($content) == ''){
                $data['pin_id'] = 0;
                $data['msg'] = '内容不能为空';
                $data['status'] = 200;
            }else{
                $pin_id = DB::table('dev_pin')->insertGetId([
                    't_id' => $t_id,
                    't_type'=>isset($t_type) && $t_type !='' ? $t_type : 'pin',
                    'created_at' => date('Y-m-d H:i:s',time()),
                    'updated_at' => date('Y-m-d H:i:s',time()),
                    'u_id'=> $user_id,
                    'status' => 'active',
                    'like_count' =>0,
                    'p_id'=>isset($p_id) && $p_id ? $p_id : 0 ,
                    'content' => $content,
                    'location'=>$location,
                    'flag' => 99
                ]);
                if(isset($pin_id) && $pin_id){
                    $data['pin_id'] = $pin_id;
                    $data['status'] = 200;
                }else{
                    $data['pin_id'] = 0;
                    $data['status'] = 200;
                    $data['msg'] = 'fail';
                }
            }
        }

        return response()->json($data);
    }
    /**
     * 评论提交
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPinReview(Request $request){
        $user_id = $request->input('user_id');
        $t_type = $request->input('t_type');
        $content = $request->input('content');
        $t_id = $request->input('t_id');
        $wx_token = $request->input('wx_token');
        $data = [];
        if(!$this->validateWxToken($user_id,$wx_token)){
            $data['msg'] = '操作不合法';
            $data['status'] = 200;
            return response()->json($data);
        }
        $review_id = DB::table('dev_review')
            ->insertGetId([
                't_id' => $t_id,
                't_type'=>isset($t_type) && $t_type !='' ? $t_type : 'pin',
                'created_at' => date('Y-m-d H:i:s',time()),
                'updated_at' => date('Y-m-d H:i:s',time()),
                'u_id' => $user_id,
                'content' => $content,
                'status' => 'active'
            ]);
        if(isset($review_id) && $review_id){
            $review= DB::table('dev_review')
                ->leftJoin('dev_wx_users','dev_wx_users.user_id','=','dev_review.u_id')
                ->where('dev_review.id','=',$review_id)
                ->select('dev_review.*','dev_wx_users.name','dev_wx_users.avatarUrl')
                ->first();
            $review->updated_at = '刚刚';
            $data['review'] = $review;
            $data['status'] = 200;
            return response()->json($data);
        }else{
            $data['status'] = 500;
            $data['msg'] = '评论失败，请重新提交。';
            return response()->json($data);
        }
    }

    /**
     * 删除评论
     * @param Request $request
     * @return mixed
     */
    public function deletePinReview(Request $request){
        $user_id = $request->input('user_id');
        $id = $request->input('id');
        $wx_token = $request->input('wx_token');
        $data = [];
        if(!$this->validateWxToken($user_id,$wx_token)){
            $data['msg'] = '操作不合法';
            $data['status'] = 500;
            return response()->json($data);
        }
        $review = DB::table('dev_review')
            ->where('id',$id)
            ->first();
        if(isset($review) && $review){
            $pin = DB::table('dev_pin')->where('id',$review->t_id)->first();
            if($review->u_id == $user_id || $user_id == 10 || $pin->u_id ==$user_id ){
                $res = DB::table('dev_review')->where('id',$id)->update([
                    'updated_at' => date('Y-m-d H:i:s',time()),
                    'status' => 'delete'
                ]);
                $data['msg'] = '删除成功';
                $data['status'] = 200;
                return response()->json($data);
            }else{
                $data['msg'] = '没有权限操作';
                $data['status'] = 500;
                return response()->json($data);
            }
        }else{
            $data['msg'] = '评论不存在';
            $data['status'] = 500;
            return response()->json($data);
        }
    }
    /**
     * 获取想法列表
     * @param $request
     * @return mixed
     */
    public function getPins(Request $request){
        $u_id = $request->input('id');
        $type = $request->input('type');
        if(isset($type) && $type){
            $pins = DB::table('dev_pin')
                ->where('status','active')
                ->where('u_id',$u_id)
                ->where('flag',99)
                ->orderBy('flag','desc')
                ->orderBy('id','desc')
                ->paginate(5);
        }else{
            $pins = DB::table('dev_pin')
                ->where('status','active')
                ->where('flag',99)
                ->orderBy('flag','desc')
                ->orderBy('id','desc')
                ->paginate(5);
        }

//        info('------------');
        foreach ($pins as $key=>$pin){
            $pins[$key]->updated_at = DateUtil::formatDate(strtotime($pin->updated_at));
            $user = DB::table('dev_wx_users')->where('user_id',$pin->u_id)->select('name','avatarUrl','city')->first();
            $pins[$key]->user = $user;
            $pins[$key]->location = json_decode($pins[$key]->location);
            $pin_like = DB::table('dev_like')->where('type','pin')->where('like_id',$pin->id)->where('user_id',$u_id)->where('status','active')->first();
            if(isset($pin_like) && $pin_like){
                $pins[$key]->like_status = $pin_like->status;
            }else{
                $pins[$key]->like_status = 'delete';
            }
            if($pin->t_type == 'poem'){
                $poem = DB::table('dev_poem')->where('id',$pin->t_id)->select('id','title','author','dynasty','type','content','author_id','author_source_id')->first();
                $_content = null;
                if(isset(json_decode($poem->content)->content) && json_decode($poem->content)->content){
                    foreach(json_decode($poem->content)->content as $item){
                        $_content = $_content.$item;
                    }
                }
                $content = mb_substr($_content,0,50,'utf-8');
                if(mb_strlen($_content)>50){
                    $content .= '...';
                }
                $poem->content = $content;
                if($poem->author != '佚名'){
                    $author = DB::table('dev_author')->where('source_id',$poem->author_source_id)->first();
                    if($poem->author_source_id != -1){
                        $poem->author_id = $author->id;
                    }else{
                        $poem->author_id = -1;
                    }
                }else{
                    $poem->status = 'delete';
                    $poem->author_id = -1;
                }
                $pins[$key]->poem = $poem;
            }elseif($pin->t_type == 'poet'){
                $author = null;
                $hot_poems = null;
                $author = DB::table('dev_author')
                    ->where('id',$pin->t_id)
                    ->select('id','dynasty','author_name','profile','source_id')
                    ->first();
                if(file_exists('static/author/'.@$author->author_name.'.jpg')){
                    $author->avatar = asset('static/author/'.@$author->author_name.'.jpg');
                }
                $pins[$key]->poet = $author;
            }elseif($pin->t_type == 'pin'){
                $p_pin = DB::table('dev_pin')
                    ->where('id',$pin->p_id)
                    ->first();
                if($p_pin){
                    info('p_id:'.$p_pin->id);
                    $p_pin->updated_at = DateUtil::formatDate(strtotime($p_pin->updated_at));
                    $user = DB::table('dev_wx_users')->where('user_id',$p_pin->u_id)->select('name','avatarUrl','city')->first();
                    $p_pin->user = $user;
                    if($p_pin->t_type == 'pin' && $pin->t_id != $pin->p_id){
                        info('---is no ');
//                        $pin->content = $pin->content.' @'.$user->name.': '.$p_pin->content;
                        $p_pin = DB::table('dev_pin')
                            ->where('id',$pin->t_id)
                            ->first();
                        info($pin->id);
                    }
                    $p_pin->location = json_decode($p_pin->location);
                    if($p_pin->t_type == 'poem'){
                        $poem = DB::table('dev_poem')->where('id',$p_pin->t_id)->select('id','title','author','dynasty','type','content','author_id','author_source_id')->first();
                        $_content = null;
                        if(isset(json_decode($poem->content)->content) && json_decode($poem->content)->content){
                            foreach(json_decode($poem->content)->content as $item){
                                $_content = $_content.$item;
                            }
                        }
                        $content = mb_substr($_content,0,50,'utf-8');
                        if(mb_strlen($_content)>50){
                            $content .= '...';
                        }
                        $poem->content = $content;
                        if($poem->author != '佚名'){
                            $author = DB::table('dev_author')->where('source_id',$poem->author_source_id)->first();
                            if($poem->author_source_id != -1){
                                $poem->author_id = $author->id;
                            }else{
                                $poem->author_id = -1;
                            }
                        }else{
                            $poem->status = 'delete';
                            $poem->author_id = -1;
                        }
                        $p_pin->poem = $poem;
                    }elseif($p_pin->t_type == 'poet') {
                        $author = DB::table('dev_author')
                            ->where('id', $p_pin->t_id)
                            ->select('id', 'dynasty', 'author_name', 'profile', 'source_id')
                            ->first();
                        if (file_exists('static/author/' . @$author->author_name . '.jpg')) {
                            $author->avatar = asset('static/author/' . @$author->author_name . '.jpg');
                        }
                        $p_pin->poet = $author;
                    }
                }else{
                    $p_pin = null;
                }
                $pins[$key]->p_pin = $p_pin;
            }
        }
        return response()->json($pins);
    }

    /**
     * 获取某个想法的评论列表
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPinReviews(Request $request,$id){
        $reviews = DB::table('dev_review')
            ->leftJoin('dev_wx_users','dev_wx_users.user_id','=','dev_review.u_id')
            ->where('dev_review.t_id','=',$id)
            ->where('dev_review.t_type','=','pin')
            ->where('dev_review.status','=','active')
            ->select('dev_review.*','dev_wx_users.name','dev_wx_users.avatarUrl')
            ->orderBy('dev_review.id','desc')
            ->paginate(10);
        foreach ($reviews as $key=>$review){
            $reviews[$key]->updated_at = DateUtil::formatDate(strtotime($review->updated_at));
        }
        $users = DB::table('dev_like')
            ->leftJoin('dev_wx_users','dev_wx_users.user_id','=','dev_like.user_id')
            ->where('dev_like.like_id','=',$id)
            ->where('dev_like.type','=','pin')
            ->where('dev_like.status','=','active')
            ->select('dev_wx_users.user_id','dev_wx_users.name','dev_wx_users.avatarUrl')
            ->orderBy('dev_like.id','desc')
            ->paginate(5);
        $data['reviews'] = $reviews;
        $data['users'] = $users;
        return response()->json($data);
    }

    /**
     * 某个pin点赞的用户列表
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPinLikeUsers($id){
        $users = DB::table('dev_like')
            ->leftJoin('dev_wx_users','dev_wx_users.user_id','=','dev_like.user_id')
            ->where('dev_like.like_id','=',$id)
            ->where('dev_like.type','=','pin')
            ->where('dev_like.status','=','active')
            ->select('dev_wx_users.user_id as u_id','dev_wx_users.name','dev_wx_users.avatarUrl','dev_like.updated_at')
            ->orderBy('dev_like.id','desc')
            ->paginate(10);

        foreach ($users as $key=>$user){
            $users[$key]->updated_at = DateUtil::formatDate(strtotime($user->updated_at));
        }
        return response()->json($users);
    }
    /**
     * 更新想法(喜欢，删除)
     * @param $id
     * @param $type
     * @param $request
     * @return mixed
     */
    public function updatePin(Request $request,$id,$type){
        // $type like delete
        $user_id = $request->input('user_id');
        $wx_token = $request->input('wx_token');
        if(!$this->validateWxToken($user_id,$wx_token)){
            $data['msg'] = '操作不合法';
            $data['status'] = false;
            return response()->json($data);
        }
        if($type == 'like'){
            $data = $this->updateLike($wx_token,$id,$user_id);
            return response()->json($data);
        }else if($type=='update'){
            $data = array();
            $msg = null;
            $res = DB::table('dev_pin')->where('id',$id)->update([
                'status'=> 'delete',
                'updated_at'=>date('Y-m-d H:i:s',time())
            ]);
            if($res){
                $msg = '删除成功';
                $data['status'] = true;
            }else{
                $msg = '删除失败';
                $data['status'] = false;
            }
            $data['msg'] = $msg;
            return response()->json($data);
        }
    }

    /**
     * @param $wx_token
     * @param $id
     * @param $user_id
     * @return mixed
     */
    public function updateLike($wx_token,$id,$user_id){
        $data = array();
        $msg = null;
        $_data = null;
//        if(!$this->validateWxToken($user_id,$wx_token)){
//            $data['pin_id'] = 0;
//            $data['msg'] = '操作不合法';
//            $data['status'] = 'illegal';
//            return $data;
//        }
        $_res = DB::table('dev_like')
            ->where('user_id',$user_id)
            ->where('like_id',$id)
            ->where('type','pin')
            ->first();
        if(!$_res){
            // 新的like
            $res = DB::table('dev_pin')->where('id',$id)->increment("like_count");
            DB::table('dev_like')->insertGetId(
                [
                    'like_id' => $id,
                    'type' => 'pin',
                    'created_at' => date('Y-m-d H:i:s',time()),
                    'updated_at' => date('Y-m-d H:i:s',time()),
                    'user_id'=> $user_id,
                    'status' => 'active'
                ]
            );
            $msg = '喜欢成功';
            $data['status'] = 'active';
        }else{
            // 更新like状态
            if($_res->status == 'active'){
                $res = DB::table('dev_pin')->where('id',$id)->decrement("like_count");
                DB::table('dev_like')
                    ->where('user_id',$user_id)
                    ->where('id',$_res->id)
                    ->update([
                        'status' => 'delete',
                        'updated_at' => date('Y-m-d H:i:s',time())
                    ]);
                $msg = '取消喜欢成功';
                $data['status'] = 'delete';
            }else{
                $res = DB::table('dev_pin')->where('id',$id)->increment("like_count");
                DB::table('dev_like')
                    ->where('user_id',$user_id)
                    ->where('id',$_res->id)
                    ->update([
                        'status' => 'active',
                        'updated_at' => date('Y-m-d H:i:s',time()),
                    ]);
                $msg = '喜欢成功';
                $data['status'] = 'active';
            }
        }
        if($res){
            $data['msg'] = $msg;
            return $data;
        }else{
            $data['msg'] = '操作失败';
            $data['status'] = 'fail';
            return $data;
        }
    }

    /**
     * 获取某个pin的详细信息
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function getPinDetail(Request $request,$id){
        $data = array();
        $msg = null;
        $u_id = $request->input('user_id');
        $pin = DB::table('dev_pin')
            ->leftJoin('dev_wx_users','dev_wx_users.user_id','=','dev_pin.u_id')
            ->where('dev_pin.id','=',$id)
            ->select('dev_pin.*','dev_wx_users.name')
            ->first();
        if(isset($pin) && $pin){
            $pin->updated_at = DateUtil::formatDate(strtotime($pin->updated_at));
            $user = DB::table('dev_wx_users')->where('user_id',$pin->u_id)->select('name','avatarUrl','city')->first();
            $pin->user = $user;
            $pin_like = DB::table('dev_like')->where('type','pin')->where('like_id',$pin->id)->where('user_id',$u_id)->where('status','active')->first();
            if(isset($pin_like) && $pin_like){
                $pin->like_status = $pin_like->status;
            }else{
                $pin->like_status = 'delete';
            }
            if($pin->t_type == 'poem'){
                $poem = DB::table('dev_poem')
                    ->where('id',$pin->t_id)
                    ->select('id','title','author','dynasty','text_content','type','author_id')
                    ->first();
                $data['poem'] = $poem;
            }
            if($pin->t_type == 'poet'){
                $poet = DB::table('dev_author')
                    ->where('id',$pin->t_id)
                    ->select('id','dynasty','author_name','profile')
                    ->first();
                $data['poet'] = $poet;

            }
            if($pin->t_type == 'pin' && $pin->p_id>0){
                $p_pin = DB::table('dev_pin')
                    ->leftJoin('dev_wx_users','dev_wx_users.user_id','=','dev_pin.u_id')
                    ->where('dev_pin.id','=',$pin->t_id)
                    ->select('dev_pin.*','dev_wx_users.name')
                    ->first();
                if($p_pin->t_type == 'pin'){
                    $pin->p_id = $p_pin->id;
//                    $pin->id = $p_pin->id;
                }
//                $pin->content = $pin->content.' @'.$p_pin->name.': '.$p_pin->content;
                if($p_pin->t_type == 'poem'){
                    $poem = DB::table('dev_poem')
                        ->where('id',$p_pin->t_id)
                        ->select('id','title','author','dynasty','text_content','type','author_id')
                        ->first();
                    $data['poem'] = $poem;
                    $pin->t_type = 'poem';
                    $pin->t_id = $poem->id;
                }
                if($p_pin->t_type == 'poet'){
                    $poet = DB::table('dev_author')
                        ->where('id',$p_pin->t_id)
                        ->select('id','dynasty','author_name','profile')
                        ->first();
                    $data['poet'] = $poet;
                    $pin->t_type = 'poet';
                    $pin->t_id = $poet->id;
                }
                if($p_pin->t_type == 'pin'){
                    $pin->pin = $p_pin;
                }
            }
            $pin->location = json_decode($pin->location);
            $data['pin'] = $pin;
        }else{
            $data['msg'] = '查找的内容不存在';
            $data['pin'] = null;
        }

        return response()->json($data);
    }

}