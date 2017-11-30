<?php
/**
 * Controller Poems
 */

namespace App\Http\Controllers\Frontend;

use DB;
use Auth;
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
        $poem_types = DB::table('poem_type')->get();
        $poem_dynasty = DB::table('poem_dynasty')->get();
        $_url = 'poem?';
        $_poems = $poems = DB::table('dev_poem');
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

        $_poems->orderBy('like_count','desc');
        $_poems = $_poems->paginate(10)->setPath($_url);
        if(!Auth::guest()){
            foreach ($_poems as $poem){
                $res = DB::table('dev_like')
                    ->where('user_id',Auth::user()->id)
                    ->where('like_id',$poem->id)
                    ->where('type','poem')->first();
                if(isset($res) && $res->status == 'active'){
                    $poem->status = 'active';
                }else{
                    $poem->status = 'delete';
                }
            }
        }
        return view('frontend.poem.index')
            ->with('query','poems')
            ->with('site_title','诗文')
            ->with('type',$type)
            ->with('dynasty',$dynasty)
            ->with('poem_types',$poem_types)
            ->with('poem_dynasty',$poem_dynasty)
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
            $_res = DB::table('dev_like')
                ->where('user_id',Auth::user()->id)
                ->where('like_id',$poem->id)
                ->where('type','poem')->first();
            if(isset($_res) && $_res->status == 'active'){
                $poem->status = 'active';
            }else{
                $poem->status = 'delete';
            }
            $hot_poems = null;
            $poems_count = 0;
            if($poem->author != '佚名'){
                $author = DB::table('dev_author')->where('author_name',$poem->author)->where('dynasty',$poem->dynasty)->first();
                $poems_count = DB::table('dev_poem')
                    ->where('author',$poem->author)
                    ->where('dynasty',$poem->dynasty)
                    ->count();
                $hot_poems = DB::table('dev_poem')
                    ->where('author',$poem->author)
                    ->where('dynasty',$poem->dynasty)
                    ->orderBy('like_count','desc')
                    ->paginate(5);
                $res = DB::table('dev_like')
                    ->where('user_id',Auth::user()->id)
                    ->where('like_id',$poem->id)
                    ->where('type','author')->first();
                if(isset($res) && $res->status == 'active'){
                    $author->status = 'active';
                }else{
                    $author->status = 'delete';
                }
            }
            return view('frontend.poem.show')
                ->with('author',$author)
                ->with('detail',$poem_detail)
                ->with('hot_poems',$hot_poems)
                ->with('poems_count',$poems_count)
                ->with('site_title',$poem->title)
                ->with('poem',$poem);
        }else{
            return view('errors.404');
        }
    }
//    /**
//     * update poem database
//     */
//    public function updatePoemLikeCount(){
//        $poems = DB::table('dev_poem_detail')->get();
//        foreach ($poems as $key=>$poem){
//            $res = DB::table('dev_poem')
//                ->where('id',$poem->poem_id)
//                ->update([
//                    'type'=>$poem->poem_type,
//                    'like_count' =>$poem->like_count
//                ]);
////            print($key.'-----'.$poem->title.'<br>');
//            if(!$res){
//                break;
//            }
//            if($key+1 == count($poems)){
//                print('ok!');
//            }
//        }
//    }

    /**
     * 更新 poem 的like_count
     * @param $request
     * @return mixed
     */
    public function updateLikeCount(Request $request){
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
                }
            }
        }else{
            $msg = '登录数据才能保存下来哦！';
        }
        if($res){
            $data['status'] = 'success';
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
    public function collect(Request $request){
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
            }else{
                // 更新like状态
                $res = DB::table($table_name)
                    ->where('id',$id)
                    ->decrement("like_count");
                $_data = DB::table($table_name)->where('id',$id)->first();
                if($_res->status == 'active'){
                    DB::table('dev_like')
                        ->where('user_id',Auth::user()->id)
                        ->where('id',$_res->id)
                        ->update([
                            'status' => 'delete',
                            'updated_at' => date('Y-m-d H:i:s',time())
                        ]);
                    $msg = '取消喜欢成功';
                }else{
                    DB::table('dev_like')
                        ->where('user_id',Auth::user()->id)
                        ->where('id',$_res->id)
                        ->update([
                            'status' => 'active',
                            'updated_at' => date('Y-m-d H:i:s',time()),
                        ]);
                    $msg = '喜欢+1';
                }
            }
        }else{
            $msg = '登录数据才能保存下来哦！';
        }
        if($res){
            $data['status'] = 'success';
            $data['msg'] = $msg;
            $data['num'] = $_data->like_count;
            return response()->json($data);
        }else{
            $data['msg'] = $msg;
            $data['status'] = 'fail';
            return response()->json($data);
        }
    }
}