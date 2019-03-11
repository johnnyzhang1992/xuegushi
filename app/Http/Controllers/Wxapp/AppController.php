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
use App\Helpers\DateUtil;
use \stdClass;
//use phpDocumentor\Reflection\Types\Array_;


class AppController extends Controller {

    private $appid;
    private $secret;
    private $sessionKey;
    private $code2session_url;
    private $accessToken_url;
    private $accessToken;
    private $wxacode_url;
    private $templateMessage_url;
    /**
     * Constructor
     */
    public function __construct () {
        parent::__construct();
        $this->appid = config('wxxcx.appid', '');
        $this->secret = config('wxxcx.secret', '');
        $this->code2session_url = config('wxxcx.code2session_url', '');
        $this->accessToken_url = config('wxxcx.accessToken_url','');
        $this->wxacode_url = config('wxxcx.wxacode_url');
        $this->templateMessage_url = config('wxxcx.templateMessage_url');
    }

    /**
     * 验证微信token的有效性
     * @param $token
     * @param $u_id
     * @return boolean
     */
    public function validateWxToken($u_id,$token){
        $user = DB::table('users')->where('id',$u_id)->first();
        if(isset($user) && $user->wx_token == $token){
            // 验证通过
            return true;
        }else{
            return false;
        }
    }
    /**
     * 获取用户地理信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserLocation(){
        $_record = geoip_record_by_name($_SERVER['REMOTE_ADDR']);
        return response()->json($_record);
    }
    /**
     * 创建新用户
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function wxUserCreate(Request $request){
        //code 在小程序端使用 wx.login 获取
        $code = $request->input('code', '');
        //encryptedData 和 iv 在小程序端使用 wx.getUserInfo 获取
        $encryptedData = $request->input('encryptedData', '');
        $iv = $request->input('iv', '');
        $systemInfo = $request->input('systemInfo','');
        $session = $this->authCodeAndCode2session($code);

        \Log::info($session);
        $data = [];
        \Log::info('session_key1----'.$this->sessionKey);
        if($this->sessionKey) {
            $_openid = $session['openid'];
            $user_id = 0;
            $_user_info = $this->decryptData($this->sessionKey,$encryptedData, $iv);
            \Log::info($_user_info);
            $_user = $_user_info['result'];
            if(isset($_user->openId)) {
                // 信息解密成功
                //判断用户是否存在
                $_u = DB::table('users')->where('openid', $_openid)->first();
                // 生成32位随机字符串
                $wx_token = md5(uniqid(microtime(true),true));
                if (isset($_u) && $_u) {
                    // 用户已经存在
                    DB::table('users')->where('openid',$_user->openId)->update([
                        'name' => $_user->nickName,
                        'updated_at' => date('Y-m-d H:i:s',time()),
                        'wx_token' => $wx_token
                    ]);
                    $user_id= $_u->id;
                    $_wx_user = DB::table('dev_wx_users')->where('openId', $_user->openId)->first();
                    if(!isset($_wx_user) && !$_wx_user){
                        $_data = [
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
                        $_data['user_id'] = $_u->id;
                        // 微信数据入库
                        DB::table('dev_wx_users')->insertGetId($_data);
                    }else{
                        // 微信最新信息采集
                        DB::table('dev_wx_users')->where('openId',$_user->openId)->update([
                            'systemInfo'=>$systemInfo,
                            'name' => $_user->nickName,
                            'updated_at' => date('Y-m-d H:i:s',time()),
                            'timestamp'=> $_user->watermark->timestamp
                        ]);
                    }
                }else{
                    \Log::info('----user----creating---');
                    //新用户创建，信息入库
                    if($_user){
                        $data = [
                            'name' => $_user->nickName,
                            'email' => $_user->openId.'@xuegushi.cn',
                            'password' => bcrypt('123456'),
                            'context_id' => '0',
                            'type' => "Employee",
                            'openid'=> $_user->openId,
                            'created_at' => date('Y-m-d H:i:s',time()),
                            'updated_at' => date('Y-m-d H:i:s',time()),
                            'wx_token' => $wx_token
                        ];
                        $user_id =  DB::table('users')->insertGetId($data);
                    }
                    $_data = [
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
                    $_data['user_id'] = $user_id;
                    DB::table('dev_wx_users')->insertGetId($_data);
                }
                $_user->user_id = $user_id;
                $_user->wx_token = $wx_token;
                return response()->json($_user);
            }else{
                return response()->json($_user);
            }
        }
        // 解密失败
        $data['status'] = 500;
        $data['message'] = '解密失败，无法正常创建用户。';
        return response()->json($data);
    }
    /**
     * 小程序登录获取用户信息
     * @param $request
     * @throws
     * @return mixed
     */
    public function getWxUserInfo(Request $request){
        //code 在小程序端使用 wx.login 获取
        $code = $request->input('code', '');
        $systemInfo = $request->input('systemInfo','');

        //根据 code 获取用户 session_key 等信息, 返回用户openid 和 session_key
        $session = $this->authCodeAndCode2session($code);
        \Log::info($session);
        \Log::info('----login-----');
        $data = [];
        // 从微信服务器成功获取session
        if(isset($session) && $this->sessionKey) {
            $openId = $session['openid'];
            \Log::info('openid-----'.$openId);
            $_u = DB::table('users')->where('openid', $openId)->first();
            $wx_token = md5(uniqid(microtime(true),true));
            if (isset($_u) && $_u) {
                // 用户已经存在
                DB::table('users')->where('openid',$openId)->update([
                    'updated_at' => date('Y-m-d H:i:s',time()),
                    'wx_token' => $wx_token
                ]);
                $_wx_user = DB::table('dev_wx_users')->where('openId', $openId)->first();
                $data['user_id'] = $_u->id;
                if(isset($_wx_user) && $_wx_user){
                    // 微信最新信息采集
                    DB::table('dev_wx_users')->where('openId',$openId)->update([
                        'systemInfo'=>$systemInfo,
                        'updated_at' => date('Y-m-d H:i:s',time()),
                    ]);
                    $_wx_user->user_id = $_u->id;
                    $_wx_user->wx_token = $wx_token;
                    return response()->json($_wx_user);
                }
            }else{
                \Log::info('用户当前未注册');
                $data['status'] = 200;
                $data['message'] = '当前用户还未注册';
            }
        }else{
            \Log::info('小程序登陆失败');
            $data['status'] = 500;
            $data['message'] = '小程序登陆失败!';
        }
        return response()->json($data);
    }

    /**
     * 用户收藏列表
     * @param $user_id
     * @param $request
     * @return mixed
     */
    public function getUserCollect(Request $request,$user_id){
        $data = null;
        $type = $request->input('type');
        if($type== 'poem'){
            $data = DB::table('dev_collect')
                ->where('dev_collect.type','poem')
                ->where('dev_collect.status','active')
                ->where('dev_collect.user_id',$user_id)
                ->leftJoin('dev_poem','dev_poem.id','=','dev_collect.like_id')
                ->select('dev_collect.*','dev_poem.title','dev_poem.author','dev_poem.dynasty','dev_poem.id as poem_id','dev_poem.like_count','dev_poem.collect_count','dev_poem.content')
                ->orderBy('dev_collect.id','desc')
                ->paginate(10);
            foreach ($data as $key=>$poem){
                $_content = null;
                if(isset(json_decode($poem->content)->content) && json_decode($poem->content)->content){
                    foreach(json_decode($poem->content)->content as $item){
                        $_content = $_content.$item;
                    }
                }
                $data[$key]->content = mb_substr($_content,0,35,'utf-8');
            }
        }elseif ($type=='sentence'){
            $data = DB::table('dev_collect')
                ->where('dev_collect.type','sentence')
                ->where('dev_collect.status','active')
                ->where('dev_collect.user_id',$user_id)
                ->leftJoin('dev_sentence','dev_sentence.id','=','dev_collect.like_id')
                ->select('dev_collect.*','dev_sentence.title','dev_sentence.origin','dev_sentence.like_count','dev_sentence.collect_count','dev_sentence.content')
                ->orderBy('dev_collect.id','desc')
                ->paginate(10);
        }elseif($type=='author'){
            $data =  DB::table('dev_collect')
                ->where('dev_collect.type','author')
                ->where('dev_collect.status','active')
                ->where('dev_collect.user_id',$user_id)
                ->leftJoin('dev_author','dev_author.id','=','dev_collect.like_id')
                ->select('dev_collect.*','dev_author.author_name','dev_author.dynasty','dev_author.id as author_id','dev_author.like_count','dev_author.collect_count')
                ->orderBy('dev_collect.id','desc')
                ->paginate(10);
        }
        foreach ($data as $key=>$item){
            $data[$key]->updated_at = DateUtil::formatDate(strtotime($item->created_at));
        }
        $res = [];
        $res['data'] = $data;
        $res['user_id'] = $user_id;
        $res['type'] = $type;
        return response()->json($res);
    }

    /**
     * 获取用户的基本信息
     * @param $user_id
     * @return mixed
     */
    public function getUserInfo($user_id){

        $poem_count = DB::table('dev_collect')
            ->where('dev_collect.type','poem')
            ->where('dev_collect.status','active')
            ->where('dev_collect.user_id',$user_id)
            ->count();
        $poet_count = DB::table('dev_collect')
            ->where('dev_collect.type','author')
            ->where('dev_collect.status','active')
            ->where('dev_collect.user_id',$user_id)
            ->count();
        $sentence_count = DB::table('dev_collect')
            ->where('dev_collect.type','sentence')
            ->where('dev_collect.status','active')
            ->where('dev_collect.user_id',$user_id)
            ->count();
        $today = date('Y-m-d',time()).' 00:00:00';
        $_t_users = DB::table('dev_wx_users')
            ->where('created_at','>',$today)
            ->count();
        $today = date('Y-m-d',time()).' 00:00:00';
        $s_total= DB::table('dev_search')
            ->where('status','active')
            ->where('created_at','>',$today)
            ->count();
        $_user_count = DB::table('dev_wx_users')->count();
        return response()->json([
            'poem_count' => $poem_count,
            'poet_count' => $poet_count,
            'sentence_count' => $sentence_count,
            's_count' => $s_total,
            'u_t_count' => $_t_users,
            'u_count' => $_user_count
        ]);
    }

    /**
     * 获取微信用户列表
     */
    public function getUserList(){
        $users = DB::table('dev_wx_users')
            ->orderBy('id','desc')
            ->paginate(10);
        return response()->json($users);
    }

    /**
     * 解密
     * @param $sessionKey
     * @param $encryptedData
     * @param $iv
     * @return int
     */
    public function decryptData($sessionKey,$encryptedData, $iv){
        $error_data = [];
        $error_data['code'] = 0;
        \Log::info('---解码-----');
        if (strlen($sessionKey) != 24) {
            $error_data['code'] = -41001;
            $error_data['message'] = 'encodingAesKey 非法';
        }
        $aesKey=base64_decode($sessionKey);

        if (strlen($iv) != 24) {
            $error_data['code'] = -41002;
            $error_data['message'] = 'encodingAesKey 非法';
        }
        $aesIV=base64_decode($iv);

        $aesCipher=base64_decode($encryptedData);

        $result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

        $dataObj=json_decode( $result );
        if( $dataObj  == NULL ) {
            $error_data['code'] = -41003;
            $error_data['message'] = 'aes 解密失败';
        }else{
//            \Log::info($dataObj);
            if( $dataObj->watermark->appid != $this->appid ) {
                $error_data['code'] = -41003;
                $error_data['message'] = 'aes 解密失败';
            }
        }
        $data['result'] = $dataObj;
        $data['error'] = $error_data;
        return $data;
    }

    /**
     * error code 说明.
     * -41001: encodingAesKey 非法
     * -41003: aes 解密失败</li>
     * -41004: 解密后得到的buffer非法
     * -41005: base64加密失败
     * -41016: base64解密失败
     *
     */

    /**
     * 根据 code 获取 session_key 等相关信息
     * @param $code
     * @throws \Exception
     * @return mixed
     */
    private function authCodeAndCode2session($code){
        $code2session_url = sprintf($this->code2session_url,$this->appid,$this->secret,$code);
        $userInfo = $this->httpRequest($code2session_url);
        if(!isset($userInfo['session_key'])){
            return [
                'code' => 10000,
                'message' => '获取 session_key 失败',
            ];
        }
        $this->sessionKey = $userInfo['session_key'];
        \Log::info('session_key----'.$userInfo['session_key']);
        return $userInfo;
    }

    /**
     * 生成小程序码
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWXACode(Request $request){
        $path = $request->input('path');
        $width = $request->input('width');
        $auto_color = $request->input('auto_color');
        $line_color = $request->input('line_color');
        $is_hyaline = $request->input('is_hyaline');
        $type = $request->input('type');
        $target_id = $request->input('target_id');
        $path = isset($path) && $path != '' ? $path : 'pages/index/index';
        $file_path = public_path('static/wxapp/');
        if(is_dir($file_path)) {
            // ok,
        } else {
            if(mkdir($file_path, 0777, true)) {
                // ok,
            } else {
                // not ok,
                $_ret = new \App\Messages\BasicErrorMessage();
                $_ret->code = 500;
                $_ret->message = "创建目录失败";

                return Response::json($_ret);
            }
        }
        $file_name = isset($type) && $type ? $type.'_'.$target_id.'.png' : 'xcx.png';
        if(file_exists($file_path.$file_name)){
            return \response()->json([
                'status' => 200,
                'file_name' => 'static/wxapp/' .$file_name,
                'message' => '图片已存在'
            ]);
        } else{
            // 先生成 accessToken ,有效时间俩小时
            $access_token = $this->getAccessToken();
            if(isset($access_token) && $access_token){
                // 正确获取 accessToken
                if(isset($access_token['errcode']) && $access_token['errcode']){
                    return \response()->json($access_token);
                }else{
                    $data = array(
                        'path' => $path,
                        'width'=> $width ? $width : 300,
                        'auto_color'=> isset($auto_color) ? $auto_color : true,
//                    'line_color'=> '{"r":"xxx","g":"xxx","b":"xxx"}',
                        'is_hyaline' => isset($is_hyaline) ? $is_hyaline : true
                    );
                    $code_url = sprintf($this->wxacode_url,$access_token['access_token']);
//                    print $code_url;
                    $_code = $this->httpRequest($code_url,$data);
//                    print $_code;
                    $sData = file_get_contents("php://input");
                    chmod($file_path, 0777);
                    if(!is_object($_code)){
                        file_put_contents($file_path.$file_name,$_code);
                        chmod($file_path.$file_name, 0777);
                        return \response()->json([
                            'status' => 200,
                            'file_name' => '/static/wxapp/' .$file_name,
                            'message' => '小程序码生成成功'
                        ]);
                    }else{
                        return \response()->json([
                            'status' => 500,
                            'message' => '小程序码生成失败',
                            'error_data' => json_decode($_code)
                        ]);
                    }
                }
            }else{
                return response()->json([
                    'status' => 500,
                    'message' => '获取 accessToken 失败'
                ]);
            }
        }
    }
    /**
     * 获取 accessToken
     * @return mixed
     */
    private function getAccessToken(){
        // accessToken_url
        $accessToken_url = sprintf($this->accessToken_url,$this->appid,$this->secret);
        $token = $this->httpRequest($accessToken_url);
        return $token;
    }
    /**
     * 请求小程序api
     * @date   2017-05-27T11:51:10+0800
     * @param  [type]                   $url  [description]
     * @param  [type]                   $data [description]
     * @return mixed
     */
    private function httpRequest($url, $data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data) && $data){
            $data = json_encode($data,JSON_UNESCAPED_UNICODE);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data))
            );
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($curl);
            if($output === FALSE ){
                return false;
            }
            curl_close($curl);
            return $output;
        }else{
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($curl);
            info('-----get---access_token------');
            if($output === FALSE ){
                return false;
            }
            curl_close($curl);
            return json_decode($output,JSON_UNESCAPED_UNICODE);
        }

    }

    /**
     * 同url下载头像到服务器
     * @param $url
     * @param $user
     * @return string
     */
    public function downloadAvatar($url,$user) {

        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt ( $ch, CURLOPT_URL, $url );
        ob_start ();
        curl_exec ( $ch );
        $return_content = ob_get_contents ();
        ob_end_clean ();
        $return_code = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );

        $_filename  = 'avatar-' . strtolower(base64_encode($user . '-' . str_random(4))) . '.jpg';
        file_put_contents(config('file.avatar_path') .'/'.$_filename,$return_content);
        /* 压缩头像 */
        $tmp_file=explode('.',$_filename);

        copy(config('file.avatar_path') .'/'.$_filename,config('file.avatar_path').'/'.$tmp_file[0].'-orign.'.$tmp_file[1]);
        ImageUtil::makeAvatar(config('file.avatar_path').'/'.$_filename , config('file.avatar_path').'/'.$_filename,200,200);

        return config('file.avatar_url') .'/'.$_filename;
    }
}