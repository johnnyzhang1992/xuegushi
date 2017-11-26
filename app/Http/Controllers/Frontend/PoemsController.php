<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers\Frontend;

use DB;
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
        if($type){
            $poems = DB::table('dev_poem')
                ->where('type',$type)
                ->orderBy('like_count','desc')
                ->paginate(10);
            $poems->setPath('poem?type='.$type);
            return view('frontend.poem.index')
                ->with('query','poems')
                ->with('type',$type)
                ->with('site_title','诗文')
                ->with('poems',$poems);
        }else{
            $poems = DB::table('dev_poem')
                ->orderBy('like_count','desc')
                ->paginate(10);
            return view('frontend.poem.index')
                ->with('query','poems')
                ->with('site_title','诗文')
                ->with('poems',$poems);
        }
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
                $author = DB::table('author')->where('author_name',$poem->author)->where('dynasty',$poem->dynasty)->first();
                $poems_count = DB::table('dev_poem')
                    ->where('author',$poem->author)
                    ->where('dynasty',$poem->dynasty)
                    ->count();
                $hot_poems = DB::table('dev_poem')
                    ->where('author',$poem->author)
                    ->where('dynasty',$poem->dynasty)
                    ->orderBy('like_count','desc')
                    ->paginate(5);
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
    /**
     * update poem database
     */
    public function updatePoemLikeCount(){
        $poems = DB::table('dev_poem_detail')->get();
        foreach ($poems as $key=>$poem){
            $res = DB::table('dev_poem')
                ->where('id',$poem->poem_id)
                ->update([
                    'type'=>$poem->poem_type,
                    'like_count' =>$poem->like_count
                ]);
//            print($key.'-----'.$poem->title.'<br>');
            if(!$res){
                break;
            }
            if($key+1 == count($poems)){
                print('ok!');
            }
        }
    }

}