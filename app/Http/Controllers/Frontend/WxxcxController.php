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
use App\Helpers\DateUtil;

class WxxcxController extends Controller
{
    protected $wxxcx;

    function __construct(Wxxcx $wxxcx)
    {
        $this->wxxcx = $wxxcx;
    }

    /**
     * 小程序登录获取用户信息
     * @param $request
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
        $dynastys = ['全部','先秦','两汉','魏晋','南北朝','隋代','唐代','五代','宋代','金朝','元代','明代','清代','近代'];
        $authors = DB::table('dev_author');
        if($dynasty){
            if($dynasty != '全部'){
                $authors->where('dynasty',$dynasty);
            }
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
        $data = array();
        $msg = null;
        $_data = null;
        $table_name = 'dev_'.$type;
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
        return response()->json([
            'p_count' => $p_count,
            'a_count' => $a_count
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
}
