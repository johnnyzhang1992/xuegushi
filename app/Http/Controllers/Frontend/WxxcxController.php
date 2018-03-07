<?php
/**
 * Created by PhpStorm.
 * User: zq199
 * Date: 2018/03/02
 * Time: 15:49
 */

namespace App\Http\Controllers\Frontend;

use Iwanli\Wxxcx\Wxxcx;
use App\Http\Controllers\Controller;
use Log;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class WxxcxController extends Controller
{
    protected $wxxcx;

    function __construct(Wxxcx $wxxcx)
    {
        $this->wxxcx = $wxxcx;
    }

    /**
     * @param $request
     * 小程序登录获取用户信息
     * @return mixed
     */
    public function getWxUserInfo(Request $request)
    {
        //code 在小程序端使用 wx.login 获取
        $code = $request->input('code', '');
        //encryptedData 和 iv 在小程序端使用 wx.getUserInfo 获取
        $encryptedData = $request->input('encryptedData', '');
        $iv = $request->input('iv', '');

        //根据 code 获取用户 session_key 等信息, 返回用户openid 和 session_key
        $userInfo = $this->wxxcx->getLoginInfo($code);

         $systemInfo = $request->input('systemInfo','');
        //获取解密后的用户信息
        $user =$this->wxxcx->getUserInfo($encryptedData, $iv);
        $user_id = 0;
        $_user = json_decode($user);
        if(isset($_user->openId)) {
            // 信息解密成功

            //判断用户是否存在
            $_u = DB::table('users')->where('openid', $_user->openId)->first();
            $data = [
                'user_id' => isset($_u) ? $_u->id : 0,
                'name' => $_user->nickName,
                'openId'=> $_user->openId,
                'wx_openid'=> $_user->watermark->appid,
                'city'=> $_user->city,
                'province'=> $_user->province,
                'avatarUrl'=>$_user->avatarUrl,
                'country'=> $_user->country,
                'language'=> $_user->language,
                'systemInfo'=>$systemInfo,
                'gender'=>$_user->gender,
                'created_at' => date('Y-m-d H:i:s',time()),
                'updated_at' => date('Y-m-d H:i:s',time()),
                'timestamp'=> $_user->watermark->timestamp
            ];
            if (isset($_u) && $_u) {
                DB::table('users')->where('openid',$_user->openId)->update([
                    'name' => $_user->nickName,
                    'updated_at' => date('Y-m-d H:i:s',time()),
                ]);
                $user_id= $_u->id;
                $_wx_user = DB::table('dev_wx_users')->where('openId', $_user->openId)->first();
                $data['user_id'] = $_u->id;
                if(!isset($_wx_user) && !$_wx_user){
                    DB::table('dev_wx_users')->insertGetId($data);
                }else{
                    DB::table('dev_wx_users')->where('openId',$_user->openId)->update([
                        'systemInfo'=>$systemInfo,
                        'name' => $_user->nickName,
                        'updated_at' => date('Y-m-d H:i:s',time()),
                        'timestamp'=> $_user->watermark->timestamp
                    ]);
                }
            }else{
                //用户信息入库
                $user_id = $this->createUser($_user);
                $data['user_id'] = $user_id;
                DB::table('dev_wx_users')->insertGetId($data);
            }
            $_user->user_id = $user_id;
            return response()->json($_user);
        }else{
            return response()->json($_user);
        }
    }
    /**
     * 创建新用户
     * users表
     * @param $user
     * @return mixed
     */
    public function createUser($user){
        if($user){
            $data = [
                'name' => $user->nickName,
                'email' => $user->openId.'@xuegushi.cn',
                'password' => bcrypt('123456'),
                'context_id' => '0',
                'type' => "Employee",
                'openid'=> $user->openId,
                'created_at' => date('Y-m-d H:i:s',time()),
                'updated_at' => date('Y-m-d H:i:s',time()),
            ];
            $user_id =  DB::table('users')->insertGetId($data);
            return $user_id;
        }
    }
    /**
     * 随机返回10首古诗文
     */
    public function getRandomPoem(){
        $poems = DB::table('dev_poem')
            ->whereIn('id',$this->getRandomArray(5))
            ->orderBy('like_count','desc')
            ->get();
        return response()->json($poems);
    }
    public function getRandomArray($num){
        $numbers = range (1,72439);
        shuffle ($numbers);
        $result = array_slice($numbers,0,$num);
        return $result;
    }
    /**
     * poem 详情页
     * @param $id
     * @return mixed
     */
    public function getPoemDetail($id){
        $author = null;
        $poem = DB::table('dev_poem')->where('id',$id)->first();
        $content = '';
        if(isset($poem->content) && json_decode($poem->content)){
            if(isset(json_decode($poem->content)->xu) && json_decode($poem->content)->xu){
                $content = $content.@json_decode($poem->content)->xu;
            }
            if(isset(json_decode($poem->content)->content) && json_decode($poem->content)->content){
                foreach(json_decode($poem->content)->content as $item){
                    $content = $content.@$item;
                }
            }
        }
        if($poem){
            $poem_detail = DB::table('dev_poem_detail')->where('poem_id',$id)->first();
            $hot_poems = null;
            $poems_count = 0;
            if($poem->author != '佚名'){
                $author = DB::table('dev_author')->where('source_id',$poem->author_source_id)->first();
                $poems_count = DB::table('dev_poem')
                    ->where('author',$poem->author)
                    ->where('dynasty',$poem->dynasty)
                    ->count();
                if($poem->author_source_id != -1){
                    $poem->author_id = $author->id;
                }else{
                    $poem->author_id = -1;
                }
            }else{
                $poem->status = 'delete';
                $poem->author_id = -1;
            }
            $res = [];
            $res['author'] = $author;
            $res['detail'] = $poem_detail;;
            $res['poems_count'] = $poems_count;
            $res['poem'] = $poem;
            return response()->json($res);
        }else{
            return null;
        }
    }
}
