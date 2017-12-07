<?php
/**
 * 收藏
 */

namespace App\Http\Controllers\People;

use DB;
use Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class CollectionController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request){
        $type = $request->input('type');
        if(!Auth::guest()){
            $c_poems = DB::table('dev_collect')
                ->where('dev_collect.type','poem')
                ->where('dev_collect.status','active')
                ->where('dev_collect.user_id',Auth::user()->id)
                ->leftJoin('dev_poem','dev_poem.id','=','dev_collect.like_id')
                ->select('dev_collect.*','dev_poem.title','dev_poem.author','dev_poem.dynasty','dev_poem.like_count','dev_poem.collect_count','dev_poem.content')
                ->orderBy('dev_collect.id','desc')
                ->paginate(10);
            $c_authors = DB::table('dev_collect')
                ->where('dev_collect.type','author')
                ->where('dev_collect.status','active')
                ->where('dev_collect.user_id',Auth::user()->id)
                ->leftJoin('dev_author','dev_author.id','=','dev_collect.like_id')
                ->select('dev_collect.*','dev_author.author_name','dev_author.dynasty','dev_author.like_count','dev_author.collect_count')
                ->orderBy('dev_collect.id','desc')
                ->paginate(10);
            $p_count = DB::table('dev_collect')
                ->where('type','poem')
                ->where('status','active')
                ->where('user_id',Auth::user()->id)
                ->count();
            $a_count = DB::table('dev_collect')
                ->where('type','author')
                ->where('status','active')
                ->where('user_id',Auth::user()->id)
                ->count();
            $c_authors->setPath('collections?type=authors');
            return view('frontend.collect.index')
                ->with('poems',$c_poems)
                ->with('query','collect')
                ->with('authors',$c_authors)
                ->with('a_count',$a_count)
                ->with('type',$type)
                ->with($this->getClAndLkCount())
                ->with('site_title','我的收藏')
                ->with('p_count',$p_count);
        }else{

        }
    }
}