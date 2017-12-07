<?php
/**
 * Controller Poems
 */

namespace App\Http\Controllers\Frontend;

use DB;
use Auth;
use BaiduSpeech;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class PoemsController extends Controller
{
    protected $query = null;
    /**
     * Create a new controller instance.
     *
     * @return mixed
     */
    public function __construct()
    {
        $this->query = 'poems';
    }

    /**
     * poems index page
     * @param  $request
     * @return mixed
     */
    public function index(Request $request){
        $type = $request->input('type');
        $dynasty = $request->input('dynasty');
        $tag = $request->input('tag');
        $poem_types = DB::table('poem_type')->get();
        $poem_dynasty = DB::table('poem_dynasty')->get();
        $_url = 'poem?';
        $_poems = DB::table('dev_poem');
        if($type){
            if($type != 'all'){
                $_poems->where('type',$type);
            }
            $_url = $_url.'type='.$type;
        }
        if($dynasty){
            if($dynasty != 'all'){
                $_poems->where('dynasty',$dynasty);
            }
            $_url = $_url.'&dynasty='.$dynasty;
        }
        if($tag){
           $_poems->where('tags','like','%'.$tag.'%');
            $_url = $_url.'tag='.$tag;
        }
        $_poems->orderBy('like_count','desc');
        $_poems = $_poems->paginate(10)->setPath($_url);
        if(!Auth::guest()){
            foreach ($_poems as $poem){
                $res = DB::table('dev_like')
                    ->where('user_id',Auth::user()->id)
                    ->where('like_id',$poem->id)
                    ->where('type','poem')->first();
                if($poem->author_source_id != -1){
                    $author = DB::table('dev_author')->where('source_id',$poem->author_source_id)->first();
                    $poem->author_id = $author->id;
                }else{
                    $poem->author_id = -1;
                }
                if(isset($res) && $res->status == 'active'){
                    $poem->status = 'active';
                }
                $collect = DB::table('dev_collect')
                    ->where('user_id',Auth::user()->id)
                    ->where('like_id',$poem->id)
                    ->where('type','poem')->first();
                if(isset($collect) && $collect->status == 'active'){
                    $poem->collect_status = 'active';
                }
            }
        }
        return view('frontend.poem.index')
            ->with('query','poems')
            ->with('site_title','诗文')
            ->with('type',$type)
            ->with('dynasty',$dynasty)
            ->with('tag',$tag)
            ->with('poem_types',$poem_types)
            ->with('poem_dynasty',$poem_dynasty)
            ->with($this->getClAndLkCount())
            ->with('h_authors',$this->getHotAuthors())
            ->with('poems',$_poems);
    }
    /**
     * poem 详情页
     * @param $id
     * @return mixed
     */
    public function show($id){
        $author = null;
        $poem = DB::table('dev_poem')->where('id',$id)->first();
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
                $hot_poems = DB::table('dev_poem')
                    ->where('author_source_id',$author->source_id)
                    ->orderBy('like_count','desc')
                    ->paginate(5);
                if(!Auth::guest()){
                    $_res = DB::table('dev_like')
                        ->where('user_id',Auth::user()->id)
                        ->where('like_id',$poem->id)
                        ->where('type','poem')->first();
                    if(isset($_res) && $_res->status == 'active'){
                        $poem->status = 'active';
                    }
                    $res = DB::table('dev_like')
                        ->where('user_id',Auth::user()->id)
                        ->where('like_id',$author->id)
                        ->where('type','author')->first();
                    if(isset($res) && $res->status == 'active'){
                        $author->status = 'active';
                    }
                    $collect = DB::table('dev_collect')
                        ->where('user_id',Auth::user()->id)
                        ->where('like_id',$poem->id)
                        ->where('type','poem')->first();
                    if(isset($collect) && $collect->status == 'active'){
                        $poem->collect_status = 'active';
                    }
                    $a_collect =  DB::table('dev_collect')
                        ->where('user_id',Auth::user()->id)
                        ->where('like_id',$author->id)
                        ->where('type','author')->first();
                    if(isset($a_collect) && $a_collect->status == 'active'){
                        $poem->collect_a_status = 'active';
                    }
                }
                if($poem->author_source_id != -1){
                    $poem->author_id = $author->id;
                }else{
                    $poem->author_id = -1;
                }
            }else{
                $poem->status = 'delete';
                $poem->author_id = -1;
            }
            return view('frontend.poem.show')
                ->with('author',$author)
                ->with('detail',$poem_detail)
                ->with('hot_poems',$hot_poems)
                ->with('poems_count',$poems_count)
                ->with('site_title',$poem->title)
                ->with($this->getClAndLkCount())
                ->with('h_authors',$this->getHotAuthors())
                ->with('poem',$poem);
        }else{
            return view('errors.404');
        }
    }
//    /**
//     * update poem database
//     */
//    public function updatePoemLikeCount(){
//        $start = 70000;
//        $end = 73001;
//        $poems = DB::table('poem_author')
//            ->where('id','>',$start)
//            ->where('id','<',$end)
//            ->get();
//
//        foreach ($poems as $key=>$poem){
//            if($poem->author_source_id != -1){
////                $author = DB::table('dev_author')->where('source_id',$poem->author_source_id)->first();
//                $res = DB::table('dev_poem')
//                    ->where('source_id',$poem->source_id)
//                    ->update([
//                        'author_id'=>-1,
//                        'author_source_id' =>$poem->author_source_id,
//                        'created_at' => date('Y-m-d H:i:s',time()),
//                        'updated_at' => date('Y-m-d H:i:s',time())
//                    ]);
//                //            print($key.'-----'.$poem->title.'<br>');
//                if(!$res){
//                    break;
//                }
//            }else{
//                $res = DB::table('dev_poem')
//                    ->where('source_id',$poem->source_id)
//                    ->update([
//                        'author_id'=>-1,
//                        'author_source_id' => -1,
//                        'created_at' => date('Y-m-d H:i:s',time()),
//                        'updated_at' => date('Y-m-d H:i:s',time())
//                    ]);
//                //            print($key.'-----'.$poem->title.'<br>');
//                if(!$res){
//                    break;
//                }
//            }
//            if($key+1 == count($poems)){
//                print $end;
//                print('ok!');
//            }
//        }
//    }

    /**
     * 更新 poem 的like_count
     * @param $request
     * @return mixed
     */
    public function updateLike(Request $request){
        $id = $request->input('id');
        $type = $request->input('type');
        $data = array();
        $msg = null;
        $res = false;
        $_data = null;
        $table_name = '';
        if($id && $type && !Auth::guest()){
            if($type == 'poem'){
                $table_name = 'dev_poem';
            }elseif ($type == 'author'){
                $table_name = 'dev_author';
            }
            $_res = DB::table('dev_like')
                ->where('user_id',Auth::user()->id)
                ->where('like_id',$id)
                ->where('type',trim($type))
                ->first();
            if(!$_res){
                // 新的like
                $res = DB::table($table_name)->where('id',$id)->increment("like_count");
                $_data = DB::table($table_name)->where('id',$id)->first();
                DB::table('dev_like')->insertGetId(
                    [
                        'like_id' => $id,
                        'type' => trim($type),
                        'created_at' => date('Y-m-d H:i:s',time()),
                        'updated_at' => date('Y-m-d H:i:s',time()),
                        'user_id'=> Auth::user()->id,
                        'status' => 'active'
                    ]
                );
                $msg = '喜欢+1';
                $data['status'] = 'active';
            }else{
                // 更新like状态
                if($_res->status == 'active'){
                    $res = DB::table($table_name)->where('id',$id)->decrement("like_count");
                    $_data = DB::table($table_name)->where('id',$id)->first();
                    DB::table('dev_like')
                        ->where('user_id',Auth::user()->id)
                        ->where('id',$_res->id)
                        ->update([
                            'status' => 'delete',
                            'updated_at' => date('Y-m-d H:i:s',time())
                        ]);
                    $msg = '取消喜欢成功';
                    $data['status'] = 'delete';
                }else{
                    $res = DB::table($table_name)->where('id',$id)->increment("like_count");
                    $_data = DB::table($table_name)->where('id',$id)->first();
                    DB::table('dev_like')
                        ->where('user_id',Auth::user()->id)
                        ->where('id',$_res->id)
                        ->update([
                            'status' => 'active',
                            'updated_at' => date('Y-m-d H:i:s',time()),
                        ]);
                    $msg = '喜欢+1';
                    $data['status'] = 'active';
                }
            }
        }else{
            $msg = '登录数据才能保存下来哦！';
        }
        if($res){
            $data['msg'] = $msg;
            $data['num'] = $_data->like_count;
            return response()->json($data);
        }else{
            $data['msg'] = $msg;
            $data['status'] = 'fail';
            return response()->json($data);
        }
    }
    /**
     * 判断是否like
     * @param $request
     * @return mixed
     */
    public function judgeLike(Request $request){
        $id = $request->input('id');
        $type = $request->input('type');
        $data = array();
        if($id && $type){
            $_res = DB::table('dev_like')->where('like_id',$id)->where('type','poem')->first();
            if($_res){
                if($_res->status == 'active'){
                    $data['status'] = true;
                }else{
                    $data['status'] = false;
                }
            }else{
                $data['status'] = false ;
            }
        }else{
            $data['status'] = false ;
        }
        return response()->json($data);
    }
    /**
     * 收藏功能
     * @param $request
     * @return mixed
     */
    public function updateCollect(Request $request){
        $id = $request->input('id');
        $type = $request->input('type');
        $data = array();
        $msg = null;
        $res = false;
        $_data = null;
        $table_name = '';
        if($id && $type && !Auth::guest()){
            if($type == 'poem'){
                $table_name = 'dev_poem';
            }elseif ($type == 'author'){
                $table_name = 'dev_author';
            }
            $_res = DB::table('dev_collect')
                ->where('user_id',Auth::user()->id)
                ->where('like_id',$id)
                ->where('type',trim($type))
                ->first();
            if(!$_res){
                // 新的like
                $res = DB::table($table_name)->where('id',$id)->increment("collect_count");
                $_data = DB::table($table_name)->where('id',$id)->first();
                DB::table('dev_collect')->insertGetId(
                    [
                        'like_id' => $id,
                        'type' => trim($type),
                        'created_at' => date('Y-m-d H:i:s',time()),
                        'updated_at' => date('Y-m-d H:i:s',time()),
                        'user_id'=> Auth::user()->id,
                        'status' => 'active'
                    ]
                );
                $msg = '收藏成功';
                $data['status'] = 'active';
            }else{
                // 更新like状态
                if($_res->status == 'active'){
                    $res = DB::table($table_name)->where('id',$id)->decrement("collect_count");
                    $_data = DB::table($table_name)->where('id',$id)->first();
                    DB::table('dev_collect')
                        ->where('user_id',Auth::user()->id)
                        ->where('id',$_res->id)
                        ->update([
                            'status' => 'delete',
                            'updated_at' => date('Y-m-d H:i:s',time())
                        ]);
                    $msg = '取消收藏成功';
                    $data['status'] = 'delete';
                }else{
                    $res = DB::table($table_name)->where('id',$id)->increment("collect_count");
                    DB::table('dev_collect')
                        ->where('user_id',Auth::user()->id)
                        ->where('id',$_res->id)
                        ->update([
                            'status' => 'active',
                            'updated_at' => date('Y-m-d H:i:s',time()),
                        ]);
                    $msg = '收藏成功';
                    $data['status'] = 'active';
                }
            }
        }else{
            $msg = '登录数据才能保存下来哦！';
        }
        if($res){
            $data['msg'] = $msg;
            return response()->json($data);
        }else{
            $data['msg'] = $msg;
            $data['status'] = 'fail';
            return response()->json($data);
        }
    }
    /**
     * 百度语音合成
     * @param $request
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
    public function VoiceCombine(Request $request){
        $type = $request->input('type');
        $id = $request->input('id');
        $poem_text = '';
        $poem = null;
        $data = array();
        if($type && $id){
            if(file_exists('static/audios/poem'.$id.'.mp3')){
                $data['src'] = url('static/audios/poem'.$id.'.mp3');
                $data['status'] = 'success';
            }else{
                $poem = DB::table('dev_poem')->where('id',trim($id))->first();
                if($poem){
                    // poem exist
                    $poem_text = $poem_text.$poem->title.'   ';
                    $poem_text = $poem_text.$poem->dynasty.'   ';
                    $poem_text = $poem_text.$poem->author.'   ';
                    if(isset($poem->content) && $poem->content){
                        if(isset($poem->content) && json_decode($poem->content)){
                            if(isset(json_decode($poem->content)->xu) && json_decode($poem->content)->xu){
                                $poem_text = $poem_text.json_decode($poem->content)->xu.' ';
                            }
                            if(isset(json_decode($poem->content)->content) && json_decode($poem->content)->content){
                                foreach(json_decode($poem->content)->content as $item){
                                    $poem_text = $poem_text.$item.'   ';
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
                        $data['src'] = $voice['data'];
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
}