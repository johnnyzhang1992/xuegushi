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
        $tag = $request->input('tag');
        $poem_types = ['全部','诗','词','曲','文言文'];
        $poem_dynasty = ['全部','先秦','两汉','魏晋','南北朝','隋代','唐代','五代','宋代','金朝','元代','明代','清代','近代'];
        $_poems = DB::table('dev_poem');
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
        if($tag){
            $_poems->where('tags','like','%'.$tag.'%');
        }
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
        }
        $res = [];
        $res['types'] = $poem_types;
        $res['dynasty'] = $poem_dynasty;
        $res['poems'] = $_poems;
        return response()->json($res);
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
//            $res['author'] = $author;
            $res['detail'] = $poem_detail;;
            $res['poems_count'] = $poems_count;
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
        $_url = 'sentence?';
        $_sentences = DB::table('dev_sentence');
        $types = config('sentence.types');;
        if($theme){
            if($theme != 'all'){
                $_sentences->where('dev_sentence.theme','like','%'.$theme.'%');
//                $types = $this->getThemeTypes($theme);
            }
            $_url = $_url.'theme='.$theme;
        }
        if($type){
            if($type != 'all'){
                $_sentences->where('dev_sentence.type','like','%'.$type.'%');
            }
            $_url = $_url.'&type='.$type;
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
}
