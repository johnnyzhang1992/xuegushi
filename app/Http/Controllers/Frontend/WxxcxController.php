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
        $_poems = $_poems->paginate(8);
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
            $_poems[$key]->key = $_keyWord;
            $_poems[$key]->name = $poem->title;
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
            ->paginate(8);
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
     * 获取某一分类的所有古诗词
     * @param $name
     * @return mixed
     */
    public function getAllData($name){
        $data = DB::table('dev_poem_book')
            ->where('dev_poem_book.belong_name','=',$name)
            ->leftJoin('dev_poem','dev_poem.source_id','=','dev_poem_book.source_id')
            ->leftJoin('dev_author','dev_author.source_id','=','dev_poem.author_source_id')
            ->select('dev_poem.id','dev_poem.author','dev_poem.title','dev_poem.dynasty','dev_poem.like_count','dev_poem.content','dev_author.id as authorId')
            ->orderBy('dev_poem.like_count','desc')
            ->paginate(8);
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
        $authors = $authors->paginate(6);
        foreach ($authors as $index=>$author){
            if(file_exists('static/author/'.@$author->author_name.'.jpg')){
                $authors[$index]->avatar = asset('static/author/'.@$author->author_name.'.jpg');
            }else{
                $authors[$index]->avatar =null;
            }
        }
        $res = [];
        $res['poets'] = $authors;
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
            ->select('dev_sentence.title','dev_poem.id as poem_id','dev_sentence.id')
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
        if(!$this->validateWxToken($user_id,$wx_token)){
            $data['msg'] = '操作不合法';
            $data['status'] = false;
            return response()->json($data);
        }
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
