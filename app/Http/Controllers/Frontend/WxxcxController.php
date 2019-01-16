<?php
/**
 * Created by PhpStorm.
 * User: zq199
 * Date: 2018/03/02
 * Time: 15:49
 */

namespace App\Http\Controllers\Frontend;

use http\Env\Response;
use Iwanli\Wxxcx\Wxxcx;
use App\Http\Controllers\Controller;
use Log;
use Config;
use \stdClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use BaiduSpeech;
use App\Helpers\DateUtil;

class WxxcxController extends Controller
{
    protected $wxxcx;
    private $appid;
    private $secret;
    private $code2session_url;
    private $accessToken_url;
    private $wxacode_url;
    /**
     * Constructor
     */
    public function __construct (Wxxcx $wxxcx) {
//        parent::__construct();
        $this->wxxcx = $wxxcx;
        $this->appid = config('wxxcx.appid', '');
        $this->secret = config('wxxcx.secret', '');
        $this->code2session_url = config('wxxcx.code2session_url', '');
        $this->accessToken_url = config('wxxcx.accessToken_url','');
        $this->wxacode_url = config('wxxcx.wxacode_url');

    }

    /**
     * 小程序登录获取用户信息
     * @param $request
     * @throws
     * @return mixed
     */
    public function getWxUserInfo(Request $request)
    {
        //code 在小程序端使用 wx.login 获取
        $code = $request->input('code', '');
        //encryptedData 和 iv 在小程序端使用 wx.getUserInfo 获取
        $encryptedData = $request->input('encryptedData', '');
        $iv = $request->input('iv', '');
        $systemInfo = $request->input('systemInfo','');

        //根据 code 获取用户 session_key 等信息, 返回用户openid 和 session_key
        $userInfo = $this->wxxcx->getLoginInfo($code);
        if($userInfo){
            $openId = $userInfo['openid'];
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
                }
                $_wx_user->user_id = $_u->id;
                $_wx_user->wx_token = $wx_token;
                return response()->json($_wx_user);
            }
        }
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
                $data['user_id'] = $_u->id;
                if(!isset($_wx_user) && !$_wx_user){
                    // 微信数据入库
                    DB::table('dev_wx_users')->insertGetId($data);
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
                //新用户创建，信息入库
                $user_id = $this->createUser($_user,$wx_token);
                $data['user_id'] = $user_id;
                DB::table('dev_wx_users')->insertGetId($data);
            }
            $_user->user_id = $user_id;
            $_user->wx_token = $wx_token;
            return response()->json($_user);
        }else{
            return response()->json($_user);
        }
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
     * 创建新用户
     * users表
     * @param $user
     * @param $wx_token
     * @return mixed
     */
    public function createUser($user,$wx_token){
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
                'wx_token' => $wx_token
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
        $numbers = range (1,5766);
        shuffle ($numbers);
        $result = array_slice($numbers,0,$num);
        return $result;
    }
    /**
     * 诗文页面
     * @param $request
     * @return mixed
     */
    public function getPoemData(Request $request){
        $type = $request->input('type');
        $dynasty = $request->input('dynasty');
        $_type = $request->input('_type');
        $_keyWord = $request->input('keyWord');
        $poem_types = ['全部','诗','词','曲','文言文'];
        $poem_dynasty = ['全部','先秦','两汉','魏晋','南北朝','隋代','唐代','五代','宋代','金朝','元代','明代','清代','近代'];
        $_poems = DB::table('dev_poem');
        if(isset($_type) && $_type != null && $_type !='null'){
            \Log::info('----'.$_type);
           if($_type == 'tag'){
               $_poems->where('tags','like','%'.$_keyWord.'%');
           }elseif($_type =='poem'){
               $_poems->where('title','like','%'.$_keyWord.'%');
               $_poems->orWhere('text_content','like','%'.$_keyWord.'%');
           }
        }else{
            if($type){
                if($type != '全部'){
                    $_poems->where('type',$type);
                }
            }
            if($dynasty){
                if($dynasty != '全部'){
                    $_poems->where('dynasty',$dynasty);
                }
            }
        }
        $_poems->select('id','source_id','title','author','dynasty','content','type','like_count','author_id','author_source_id');
        $_poems->orderBy('like_count','desc');
        $_poems = $_poems->paginate(10);
        foreach ($_poems as $key=>$poem){
            if($poem->author_source_id != -1){
                $author = DB::table('dev_author')->where('source_id',$poem->author_source_id)->first();
                $poem->author_id = $author->id;
            }else{
                $poem->author_id = -1;
            }
            $_content = null;
            if(isset(json_decode($poem->content)->content) && json_decode($poem->content)->content){
                foreach(json_decode($poem->content)->content as $item){
                    $_content = $_content.$item;
                }
            }
            $_poems[$key]->content = mb_substr($_content,0,35,'utf-8');
            if(isset($poem->tags) && $poem->tags){
                $_tag = array();
                foreach (explode(',',$poem->tags) as $tag){
                    array_push($_tag,$tag);
                }
                $_poems[$key]->tags = $_tag;
            }
        }
        $res = [];
//        $res['types'] = $poem_types;
//        $res['dynasty'] = $poem_dynasty;
        $res['poems'] = $_poems;
        return response()->json($res);
    }
    /**
     * poem 详情页
     * @param $id
     * @param $request
     * @return mixed
     */
    public function getPoemDetail(Request $request,$id){
        $user_id = $request->input('user_id');
        $author = null;
        $poem = DB::table('dev_poem')->where('id',$id)->first();
        DB::table('dev_poem')->where('id',$id)->increment("pv_count");
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
            if($user_id == 0){
                $poem->collect_status = false;
            }else{
                $collect = DB::table('dev_collect')
                    ->where('user_id',$user_id)
                    ->where('like_id',$poem->id)
                    ->where('type','poem')->first();
                if(isset($collect) && $collect->status == 'active'){
                    $poem->collect_status = true;
                }else{
                    $poem->collect_status = false;
                }
            }
//            if(isset($poem_detail) && $poem_detail){
//                if(isset($poem_detail->zhu) && $poem_detail->zhu){
//                    $poem_detail->zhu = json_decode($poem_detail->zhu);
//                }
//            }

            $res = [];
//            $res['author'] = $author;
            $res['detail'] = $poem_detail;
            $res['poems_count'] = $poems_count;
            $res['poem'] = $poem;
            $res['bg_image'] = 'https://xuegushi.cn/static/xcx/gugong2.jpg';
            return response()->json($res);
        }else{
            return null;
        }
    }
    public function getPoemContent($id){
        $poem = DB::table('dev_poem')->where('id',$id)->first();
        DB::table('dev_poem')->where('id',$id)->increment("pv_count");
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
            $res = [];
//            $res['author'] = $author;
            $res['poem'] = $poem;
            return response()->json($res);
        }else{
            return null;
        }
    }
    /**
     * 获取首页内容
     * @param $request
     * @return mixed
     */
    public function getHomeData(Request $request){
        $name = $request->input('name');
        $name = (isset($name) && $name) ? $name : null;
        // 先获取一首推荐古诗(名句
        $data = null;
        $title = null;
        switch ($name){
            case 'songci':
                $title = '宋词精选';
                $data = $this->getData($title);
                break;
            case 'shijiu':
                $title = '古诗十九首';
                $data = $this->getData($title);
                break;
            case 'xiaoxuewyw':
                $title = '小学文言文';
                $data = $this->getData($title);
                break;
            case 'chuci':
                $title = '楚辞';
                $data = $this->getData($title);
                break;
            case 'tangshi':
                $data = $this->getAllData($name);
                break;
            case 'sanbai':
                $data = $this->getAllData($name);
                break;
            case 'songcisanbai':
                $data = $this->getAllData($name);
                break;
            case 'shijing':
                $data = $this->getAllData($name);
                break;
            case 'yuefu':
                $data = $this->getAllData($name);
                break;
            case 'xiaoxue':
                $data = $this->getAllData($name);
                break;
            case 'chuzhong':
                $data = $this->getAllData($name);
                break;
            case 'chuzhongwyw':
                $data = $this->getAllData($name);
                break;
            case 'gaozhong':
                $data = $this->getAllData($name);
                break;
            case 'gaozhongwyw':
                $data = $this->getAllData($name);
                break;
            default:
                $data = $this->getData('古诗三百首');
                break;
        }
        $res = [];
        $res['poems'] = $data;
        $res['hot'] = $this->getRandomSentence();
        return response()->json($res);
    }

    /**
     * 获取名句
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSentenceData(Request $request){
        // 先获取一首推荐古诗(名句
        $type = $request->input('type');
        $theme = $request->input('theme');
        $themes = config('sentence.themes');
        $_keyWord = $request->input('keyWord');
        $_url = 'sentence?';
        $_sentences = DB::table('dev_sentence');
        $types = config('sentence.types');;
        if($theme){
            if($theme != '全部'){
                $_sentences->where('dev_sentence.theme','like','%'.$theme.'%');
//                $types = $this->getThemeTypes($theme);
            }
            $_url = $_url.'theme='.$theme;
        }
        if($type){
            if($type != '全部'){
                $_sentences->where('dev_sentence.type','like','%'.$type.'%');
            }
            $_url = $_url.'&type='.$type;
        }
        if(isset($_keyWord) && $_keyWord){
            $_sentences ->where('dev_sentence.title','like','%'.$_keyWord.'%');
        }
        $_sentences->orderBy('dev_sentence.like_count','desc');
        $_sentences->leftJoin('dev_poem','dev_poem.source_id','=','dev_sentence.target_source_id');
        $_sentences->select('dev_sentence.*','dev_poem.id as poem_id','dev_poem.author','dev_poem.dynasty','dev_poem.title as poem_title','dev_poem.tags');
        $_sentences = $_sentences->paginate(10)->setPath($_url);

        $res = [];
        $res['poems'] = $_sentences;
        $res['type'] = $type;
        $res['theme'] = $theme;
        $res['themes'] = $themes;
        $res['types'] = $types;

        return response()->json($res);
    }
    /**
     * @param $name
     * @return mixed
     */
    public function getData($name){
        $value = '%'.$name.'%';
        $data = DB::table('dev_poem')
            ->where('dev_poem.tags','like',$value)
//            ->where('like_count','>',10)
            ->leftJoin('dev_author','dev_author.source_id','=','dev_poem.author_source_id')
            ->select('dev_poem.id','dev_poem.author','dev_poem.content','dev_poem.dynasty','dev_poem.like_count','dev_poem.title','dev_author.id as authorId')
            ->orderBy('dev_poem.like_count','desc')
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
        return $data;
    }
    public function getAllData($name){
        $data = DB::table('dev_poem_book')
            ->where('dev_poem_book.belong_name','=',$name)
            ->leftJoin('dev_poem','dev_poem.source_id','=','dev_poem_book.source_id')
            ->leftJoin('dev_author','dev_author.source_id','=','dev_poem.author_source_id')
            ->select('dev_poem.id','dev_poem.author','dev_poem.title','dev_poem.dynasty','dev_poem.like_count','dev_poem.content','dev_author.id as authorId')
            ->orderBy('dev_poem.like_count','desc')
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
        return $data;
    }
    /**
     * 获取诗人信息
     * @param $request
     * @return mixed
     */
    public function getPoetData(Request $request){
        $dynasty = $request->input('dynasty');
        $_keyWord = $request->input('keyWord');
        $dynastys = ['全部','先秦','两汉','魏晋','南北朝','隋代','唐代','五代','宋代','金朝','元代','明代','清代','近代'];
        $authors = DB::table('dev_author');
        if($dynasty){
            if($dynasty != '全部'){
                $authors->where('dynasty',$dynasty);
            }
        }
        if(isset($_keyWord) && $_keyWord){
            $authors->where('author_name','like','%'.$_keyWord.'%');
        }
        $authors->select('id','author_name','dynasty','profile')->orderBy('like_count','desc');
        $authors = $authors->paginate(10);

        $res = [];
        $res['poets'] = $authors;
        $res['dynasty'] = $dynastys;
        return response()->json($res);
    }
    /**
     * 获取诗人详情
     * @param $id
     * @param $request
     * @return mixed
     */
    public function getPoetDetailData(Request $request,$id){
        $author = null;
        $hot_poems = null;
        $user_id = $request->input('user_id');
        DB::table('dev_author')->where('id',$id)->increment("pv_count");
        $author = DB::table('dev_author')
            ->where('id',$id)
            ->select('id','dynasty','author_name','profile','source_id')
            ->first();
        if(file_exists('static/author/'.@$author->author_name.'.jpg')){
            $author->avatar = asset('static/author/'.@$author->author_name.'.jpg');
        }
        if($author->author_name != '佚名'){
            $hot_poems = DB::table('dev_poem')
                ->where('author_source_id',$author->source_id)
                ->select('dev_poem.id','dev_poem.author','dev_poem.content','dev_poem.dynasty','dev_poem.like_count','dev_poem.title')
                ->orderBy('like_count','desc')
                ->paginate(5);
            foreach ($hot_poems as $key=>$poem){
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
                if($user_id == 0){
                    $author->collect_status = false;
                }else{
                    $collect = DB::table('dev_collect')
                        ->where('user_id',$user_id)
                        ->where('like_id',$author->id)
                        ->where('type','author')->first();
                    if(isset($collect) && $collect->status == 'active'){
                        $author->collect_status = true;
                    }else{
                        $author->collect_status = false;
                    }
                }
                $hot_poems[$key]->content = $content;
            }

        }else{
            $hot_poems = [];
        }

        $res = [];
        $res['poet'] = $author;
        $res['poems'] = $hot_poems;

        return response()->json($res);
    }
    /**
     * 随机获取一条名句
     */
    public function getRandomSentence(){
        $post = DB::table('dev_sentence')
            ->leftJoin('dev_poem','dev_poem.source_id','=','dev_sentence.target_source_id')
            ->select('dev_sentence.title','dev_poem.id')
            ->whereIn('dev_sentence.id',$this->getRandomArray(1))
            ->get();
        return $post;
    }

    /**
     * 收藏诗文
     * @param Request $request
     * @param $id
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCollect(Request $request,$id,$type){
        $user_id = $request->input('user_id');
        $wx_token = $request->input('wx_token');
        $data = array();
        $msg = null;
        $_data = null;
        $table_name = 'dev_'.$type;
//        if(!$this->validateWxToken($user_id,$wx_token)){
//            $data['msg'] = '操作不合法';
//            $data['status'] = false;
//            return response()->json($data);
//        }
        $_res = DB::table('dev_collect')
            ->where('user_id',$user_id)
            ->where('like_id',$id)
            ->where('type',trim($type))
            ->first();
        if(!$_res){
            // 新的like
            $res = DB::table($table_name)->where('id',$id)->increment("collect_count");
            $res1 = DB::table('users')->where('id',$user_id)->increment("collect_count");
//                $_data = DB::table($table_name)->where('id',$id)->first();
            DB::table('dev_collect')->insertGetId(
                [
                    'like_id' => $id,
                    'type' => trim($type),
                    'created_at' => date('Y-m-d H:i:s',time()),
                    'updated_at' => date('Y-m-d H:i:s',time()),
                    'user_id'=> $user_id,
                    'status' => 'active'
                ]
            );
            $msg = '收藏成功';
            $data['status'] = true;
        }else{
            // 更新like状态
            if($_res->status == 'active'){
                $res = DB::table($table_name)->where('id',$id)->decrement("collect_count");
                $res1 = DB::table('users')->where('id',$user_id)->decrement("collect_count");
//                    $_data = DB::table($table_name)->where('id',$id)->first();
                DB::table('dev_collect')
                    ->where('user_id',$user_id)
                    ->where('id',$_res->id)
                    ->update([
                        'status' => 'delete',
                        'updated_at' => date('Y-m-d H:i:s',time())
                    ]);
                $msg = '取消收藏成功';
                $data['status'] = false;
            }else{
                $res = DB::table($table_name)->where('id',$id)->increment("collect_count");
                $res1 = DB::table('users')->where('id',$user_id)->increment("collect_count");
                DB::table('dev_collect')
                    ->where('user_id',$user_id)
                    ->where('id',$_res->id)
                    ->update([
                        'status' => 'active',
                        'updated_at' => date('Y-m-d H:i:s',time()),
                    ]);
                $msg = '收藏成功';
                $data['status'] = true;
            }
        }
        if($res){
            $data['msg'] = $msg;
            return response()->json($data);
        }else{
            $data['msg'] = $msg;
            $data['status'] = false;
            return response()->json($data);
        }
    }

    /**
     * 用户收藏列表
     * @param $user_id
     * @param $type
     * @param $request
     * @return mixed
     */
    public function getUserCollect(Request $request,$user_id,$type){
        $data = null;
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
        }else{
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
        $p_count = DB::table('dev_collect')
            ->where('dev_collect.type','poem')
            ->where('dev_collect.status','active')
            ->where('dev_collect.user_id',$user_id)
            ->count();
        $a_count = DB::table('dev_collect')
            ->where('dev_collect.type','author')
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
            'p_count' => $p_count,
            'a_count' => $a_count,
            's_count' => $s_total,
            'u_t_count' => $_t_users,
            'u_count' => $_user_count
        ]);
    }

    /**
     * 获取专栏文章
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPostData(){
        $posts = DB::table('dev_post')
            ->where('dev_post.status','active')
            ->leftJoin('users','users.id','=','dev_post.creator_id')
            ->select('dev_post.*','users.name as author_name')
            ->orderBy('dev_post.created_at','desc')
            ->orderBy('dev_post.pv_count','desc')
            ->paginate(9);
        foreach ($posts as $key=>$item){
            $posts[$key]->updated_at = DateUtil::formatDate(strtotime($item->updated_at));
            $posts[$key]->content = mb_substr(strip_tags($item->content),0,35,'utf-8');
        }
        return response()->json($posts);
    }
    public function getPostDetailData($id){
        $data = DB::table('dev_post')
            ->where('dev_post.id',$id)
            ->leftJoin('users','users.id','=','dev_post.creator_id')
            ->leftJoin('dev_zhuanlan','dev_zhuanlan.id','=','dev_post.zhuanlan_id')
            ->select('dev_post.*','users.name as user_name','users.avatar','dev_zhuanlan.alia_name as zhuan_alia_name',
                'dev_zhuanlan.about','dev_zhuanlan.avatar as zhuan_avatar','dev_zhuanlan.name as zhuan_name')
            ->first();
        $data->cover_url = url($data->cover_url);
        $data->avatar = url($data->avatar);
        $data->updated_at = DateUtil::formatDate(strtotime($data->updated_at));
        DB::table('dev_post')->where('id',$data->id)->increment("pv_count");
        return response()->json($data);
    }

    /**
     * 搜索功能
     * @param $_key
     * @return mixed
     */
    public function getSearchResult($_key){
        $_key = trim($_key);
        $this->searchRecord($_key);
        $authors = DB::table('dev_author')
            ->where('author_name','like','%'.$_key.'%')
            ->select('id','dynasty','author_name','like_count','profile')
            ->orderBy('like_count','desc')
            ->paginate(3);
        $tags = DB::table('dev_poem')
            ->where('tags','like','%'.$_key.'%')
            ->select('tags')
            ->paginate(3);
        $_tags = array();
        foreach ($tags as $tag){
            foreach(explode(',',$tag->tags) as $_tag){
                if(strpos($_tag,$_key)!== false){
                    array_push($_tags,$_tag);
                }
            }
        }
        $_tags = array_unique($_tags);
        $sentences = DB::table('dev_sentence')
            ->where('dev_sentence.title','like','%'.$_key.'%')
            ->leftJoin('dev_poem','dev_poem.source_id','=','dev_sentence.target_source_id')
            ->select('dev_sentence.title','dev_poem.id','dev_poem.author','dev_poem.dynasty','dev_poem.title as poem_title')
            ->paginate(3);
        $poems = DB::table('dev_poem')
            ->where('title','like','%'.$_key.'%')
            ->orWhere('text_content','like','%'.$_key.'%')
            ->select('id','title','author','dynasty','like_count','content')
            ->orderBy('like_count','desc')
            ->paginate(3);
        foreach ($poems as $key=>$poem){
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
            $poems[$key]->content = mb_substr($content,0,35,'utf-8');
        }
        return response()->json([
            'tags' => $_tags,
            'sentences' => $sentences,
            'poems' => $poems,
            'poets' =>$authors
        ]);
    }

    /**
     * 记录搜索记录
     * @param $key
     */
    public function searchRecord($key){
        $today = date('Y-m-d',time()).' 00:00:00';
        $_result = DB::table('dev_search')
            ->where('name',$key)
            ->where('created_at','>',$today)
            ->first();
        if(isset($_result)&& $_result){
            DB::table('dev_search')->where('id',$_result->id)->increment("count");
        }else{
            DB::table('dev_search')->insertGetId([
                'name' =>trim($key),
                'status'=>'active',
                'created_at' =>date('Y-m-d H:i:s',time())
            ]);
        }
    }

    /**
     * 获取热门搜索词
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHotSearchWord(){
        $today = date('Y-m-d',time()).' 00:00:00';
        $_result = DB::table('dev_search')
            ->where('status','active')
            ->where('created_at','>',$today)
            ->orderBy('count','desc')
            ->select('name')
            ->limit(8)
            ->get();
        $cur_length = count($_result);
        $week_time = date('Y-m-d H:i:s', strtotime($today .' -3 day'));
        if($cur_length<8){
            $limit = 8 - $cur_length;
            // 获取最近三天搜索量最多的词
            $_result1 = DB::table('dev_search')
                ->where('status','active')
                ->where('created_at','>',$week_time)
                ->where('created_at','<',$today)
                ->orderBy('count','desc')
                ->select('name')
                ->limit($limit)
                ->get();
            $_result = array_merge($_result,$_result1);
        }
        $_lists = array();
        foreach ($_result as $_list){
            array_push($_lists,mb_substr($_list->name,0,8,"UTF-8"));
        }
        return response()->json($_lists);
    }
    public function getHotSearchWord1(){
        $today = date('Y-m-d',time()).' 00:00:00';
        $_result = DB::table('dev_search')
            ->where('status','active')
            ->where('created_at','>',$today)
            ->orderBy('count','desc')
            ->select('name')
            ->limit(8)
            ->get();
        $cur_length = count($_result);
        $week_time = date('Y-m-d H:i:s', strtotime($today .' -3 day'));
        if($cur_length<8){
            $limit = 8- $cur_length;
            // 获取最近三天搜索量最多的词
            $_result1 = DB::table('dev_search')
                ->where('status','active')
                ->where('created_at','>',$week_time)
                ->where('created_at','<',$today)
                ->orderBy('count','desc')
                ->select('name')
                ->limit($limit)
                ->get();
            $_result = array_merge($_result,$_result1);
        }
        $_lists = array();
        foreach ($_result as $_list){
            array_push($_lists,$_list->name);
        }
        return response()->json($_lists);
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
     * 搜索列表
     * @param $request
     * @return mixed
     */
    public function getSearchList(Request $request){
        $list = DB::table('dev_search')
            ->where('status','active')
            ->orderBy('id','desc')
            ->paginate(10);
        return response()->json($list);
    }
    /**
     * 搜索内容状态更新
     * @param $id
     * @return mixed
     */

    public function searchUpdate($id){
        $item = DB::table('dev_search')->where('id',$id)->first();
        $res = [];
        if(isset($item) && $item){
            $result = DB::table('dev_search')->where('id',$id)->update([
                'status'=>'delete'
            ]);
            $item = DB::table('dev_search')->where('id',$id)->first();
            if($result){
                $res['status'] = 200;
                $res['message'] = 'success';
                $res['data'] = $item;
            }
        }else{
            $res['status'] = 500;
            $res['message'] = '项目不存在';
        }
        return response()->json($res);
    }
    /**
     * 百度语音合成
     * @param $id
     * @return mixed
     */
//参数	类型	说明	可为空
//text	String	合成的文本	N
//userID	String	用户唯一标识	Y
//lan	String	语言，可选值 ['zh']，默认为zh	Y
//speed	Integer	语速，取值0-9，默认为5中语速	Y
//pitch	Integer	音调，取值0-9，默认为5中语调	Y
//volume	Integer	音量，取值0-15，默认为5中音量	Y
//person	Integer	发音人选择, 0为女声，1为男声，3为情感合成-度逍遥，4为情感合成-度丫丫，默认为普通女	Y
//fileName	String	文件存储路径名称，默认存储在public/audios/目录下
    public function getVoiceCombine($id){
//        $type = $request->input('type');
//        $id = $request->input('id');
        $poem_text = '';
        $poem = null;
        $data = array();
        if($id){
            if(file_exists('static/audios/poem-'.$id.'.mp3')){
                $data['src'] = url('static/audios/poem-'.$id.'.mp3');
                $data['status'] = 'success';
            }else{
                $poem = DB::table('dev_poem')->where('id',trim($id))->first();
                if($poem){
                    // poem exist
                    $poem_text = $poem_text.$poem->title.',';
                    $poem_text = $poem_text.$poem->dynasty.',';
                    $poem_text = $poem_text.$poem->author.',,';
                    if(isset($poem->content) && $poem->content){
                        if(isset($poem->content) && json_decode($poem->content)){
                            if(isset(json_decode($poem->content)->xu) && json_decode($poem->content)->xu){
                                $poem_text = $poem_text.json_decode($poem->content)->xu.',';
                            }
                            if(isset(json_decode($poem->content)->content) && json_decode($poem->content)->content){
                                foreach(json_decode($poem->content)->content as $item){
                                    $poem_text = ','.$poem_text.$item.',';
                                }
                            }
                        }
                    }
                    $userID = config('laravel-baidu-speech.user-id','xuegushi');
                    $lan = 'zh';
                    $speed = 4;
                    $pitch = 5;
                    $volume = 8;
                    $person = 0;
                    $filename = '/static/audios/poem-'.$poem->id.'.mp3';
                    $voice = BaiduSpeech::combine($poem_text, $userID, $lan, $speed, $pitch, $volume, $person, $filename);
                    if(isset($voice['success']) && $voice['success']){
                        $data['src'] = asset($voice['data']);
                        $data['status'] = 'success';
                    }
                }else{
                    $data['status'] = 'fail';
                    $data['msg'] = '古诗文不存在！';
                }
            }

        }else{
            $data['status'] = 'fail';
            $data['msg'] = '信息不全，无法正常查询！';
        }
        return response()->json($data);
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
                'location'=>$location
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
                    'location'=>$location
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
                ->orderBy('flag','desc')
                ->orderBy('id','desc')
                ->paginate(5);
        }else{
            $pins = DB::table('dev_pin')
                ->where('status','active')
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
     * 获取话题列表，方便管理员管理
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTopics(Request $request){
        $user_id = $request->input('user_id');
        $wx_token = $request->input('wx_token');
        $data = [];
        if (!$this->validateWxToken($user_id, $wx_token)) {
            $data['msg'] = '操作不合法';
            $data['status'] = 500;
            return response()->json($data);
        }
        $topics = DB::table('dec_wx_topic')
            ->where('status','active')
            ->orderBy('id','desc')
            ->paginate(10);
        return \response()->json($topics);
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
        return \response()->json($data);
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
        return \response()->json($data);
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
        $file_path = public_path('static/wx/');
        $file_name = isset($type) && $type ? $type.'_'.$target_id.'.png' : 'xcx.png';
        if(file_exists($file_path.$file_name)){
            return \response()->json([
                'status' => 200,
                'file_name' => 'https://xuegushi.cn/static/wx/' .$file_name,
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
                            'file_name' => 'https://xuegushi.cn/static/wx/' .$file_name,
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
    private function httpRequest($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
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
            if($output === FALSE ){
                return false;
            }
            curl_close($curl);
            return json_decode($output,JSON_UNESCAPED_UNICODE);
        }

    }
}
