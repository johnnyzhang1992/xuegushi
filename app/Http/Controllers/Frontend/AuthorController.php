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
     * @return mixed
     */
    public function index()
    {
        $authors = DB::table('author')
            ->orderBy('like_count','desc')
            ->paginate(10);
        return view('frontend.author.index')
            ->with('query','authors')
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
        $author = DB::table('dev_author')->where('id',$id)->first();
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
            }else{
                $author->status = 'delete';
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
                ->with('site_title',$author->author_name)
                ->with('site_description',$site_des)
                ->with('hot_poems',$hot_poems)
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

}