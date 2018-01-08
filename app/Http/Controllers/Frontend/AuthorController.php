<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
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
class AuthorController extends Controller
{
    protected $query = null;
    /**
     * Create a new controller instance.
     *
     * @return mixed
     */
    public function __construct()
    {
        $this->query = 'authors';
    }

    /**
     * authors index page
     * @param $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $dynasty = $request->input('dynasty');
        $dynastys = DB::table('poem_dynasty')->get();
        $_url = 'author?';
        $authors = DB::table('dev_author');
        if($dynasty){
            if($dynasty != 'all'){
                $authors->where('dynasty',$dynasty);
            }
            $_url = $_url.'dynasty='.$dynasty;
        }
        $authors->orderBy('like_count','desc');
        $authors = $authors->paginate(10)->setPath($_url);
        if(!Auth::guest()){
            foreach ($authors as $author){
                $res = DB::table('dev_like')
                    ->where('user_id',Auth::user()->id)
                    ->where('like_id',$author->id)
                    ->where('type','author')->first();

                if(isset($res) && $res->status == 'active'){
                    $author->status = 'active';
                }
                $collect = DB::table('dev_collect')
                    ->where('user_id',Auth::user()->id)
                    ->where('like_id',$author->id)
                    ->where('type','author')->first();
                if(isset($collect) && $collect->status == 'active'){
                    $author->collect_status = 'active';
                }
            }
        }
        return view('frontend.author.index')
            ->with('query','authors')
            ->with('dynastys',$dynastys)
            ->with('dynasty',$dynasty)
            ->with($this->getClAndLkCount())
            ->with('h_authors',$this->getHotAuthors())
            ->with('authors',$authors);
    }
    /**
     * author 详情页
     * @param $id
     * @return mixed
     */
    public function show($id){
        $author = null;
        $hot_poems = null;
        $poems_count = 0;
        $author = DB::table('dev_author')->where('author_name',$id)->first();
        if($author){
            DB::table('dev_author')->where('author_name',$id)->increment("pv_count");
        }else{
            $author = DB::table('dev_author')->where('id',$id)->first();
            DB::table('dev_author')->where('id',$id)->increment("pv_count");
        }
        if($author){
            if(!Auth::guest()){
                $res = DB::table('dev_like')
                    ->where('user_id',Auth::user()->id)
                    ->where('like_id',$author->id)
                    ->where('type','author')->first();
                if(isset($res) && $res->status == 'active'){
                    $author->status = 'active';
                }else{
                    $author->status = 'delete';
                }
                $collect = DB::table('dev_collect')
                    ->where('user_id',Auth::user()->id)
                    ->where('like_id',$author->id)
                    ->where('type','author')->first();
                if(isset($collect) && $collect->status == 'active'){
                    $author->collect_status = 'active';
                }
            }
            if($author->author_name != '佚名'){
                $poems_count = DB::table('dev_poem')
                    ->where('author_source_id',$author->source_id)
                    ->count();
                $hot_poems = DB::table('dev_poem')
                    ->where('author_source_id',$author->source_id)
                    ->orderBy('like_count','desc')
                    ->paginate(5);
            }
            if(isset($author->profile) && $author->profile){
                $site_des = $author->profile;
            }else{
                $site_des = config('seo.default_description');
            }
            return view('frontend.author.show')
//                ->with('query','authors')
                ->with('site_title',$author->author_name.'-介绍|生平|轶事以及代表作品')
                ->with('site_description',$site_des)
                ->with('hot_poems',$hot_poems)
                ->with($this->getClAndLkCount())
                ->with('h_authors',$this->getHotAuthors())
                ->with('poems_count',$poems_count)
                ->with('author',$author);
        }else{
            return view('errors.404');
        }
    }
    /**
     * update author database
     */
    public function updateAuthorsLikeCount(){
        $authors = DB::table('author_like')->get();
        foreach ($authors as $key=>$author){
            $res = DB::table('author')->where('author_name',$author->author_name)->update(['like_count'=>$author->like_count]);
            if(!$res){
                break;
            }
            if($key+1 == count($authors)){
                print('ok!');
            }
        }
    }

    /**
     * 返回当前诗人所有的作品
     * @param $id
     * @return mixed
     */

    public function showAllPoems($id){
        $author = null;
        $hot_poems = null;
        $poems_count = 0;
        $author = DB::table('dev_author')->where('id',$id)->first();
        if($author){
            $_poems = DB::table('dev_poem')
                ->where('author_source_id',$author->source_id)
                ->orderBy('like_count','desc')
                ->paginate(10);
            if(!Auth::guest()){
                $res1 = DB::table('dev_like')
                    ->where('user_id',Auth::user()->id)
                    ->where('like_id',$author->id)
                    ->where('type','author')->first();
                if(isset($res1) && $res1->status == 'active'){
                    $author->status = 'active';
                }
                $collect = DB::table('dev_collect')
                    ->where('user_id',Auth::user()->id)
                    ->where('like_id',$author->id)
                    ->where('type','author')->first();
                if(isset($collect) && $collect->status == 'active'){
                    $author->collect_status = 'active';
                }
                foreach ($_poems as $poem) {
                    $res = DB::table('dev_like')
                        ->where('user_id', Auth::user()->id)
                        ->where('like_id', $poem->id)
                        ->where('type', 'poem')->first();
                    if ($poem->author_source_id != -1) {
                        $poem->author_id = $author->id;
                    } else {
                        $poem->author_id = -1;
                    }
                    if (isset($res) && $res->status == 'active') {
                        $poem->status = 'active';
                    }
                    $collect1 = DB::table('dev_collect')
                        ->where('user_id', Auth::user()->id)
                        ->where('like_id', $poem->id)
                        ->where('type', 'poem')->first();
                    if (isset($collect1) && $collect1->status == 'active') {
                        $poem->collect_status = 'active';
                    }
                }
            }
            if($author->author_name != '佚名'){
                $poems_count = DB::table('dev_poem')
                    ->where('author_source_id',$author->source_id)
                    ->count();
                $hot_poems = DB::table('dev_poem')
                    ->where('author_source_id',$author->source_id)
                    ->orderBy('like_count','desc')
                    ->paginate(5);
            }
            if(isset($author->profile) && $author->profile){
                $site_des = $author->profile;
            }else{
                $site_des = config('seo.default_description');
            }
            return view('frontend.author.allPoems')
//                ->with('query','authors')
                ->with('site_title',$author->author_name)
                ->with('site_description',$site_des)
                ->with('hot_poems',$hot_poems)
                ->with($this->getClAndLkCount())
                ->with('poems',$_poems)
                ->with('h_authors',$this->getHotAuthors())
                ->with('poems_count',$poems_count)
                ->with('author',$author);
        }else{
            return view('errors.404');
        }
    }
}