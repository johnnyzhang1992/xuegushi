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
class LikeController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param $_type
     * @return mixed
     */
    public function index($_type=null){
        $type = $_type;
        $c_poems = array();
        $c_authors = array();
        $c_sentences = array();
        if(!Auth::guest()){
            if(isset($type) && $type =='author'){
                $c_authors = DB::table('dev_like')
                    ->where('dev_like.type','author')
                    ->where('dev_like.status','active')
                    ->where('dev_like.user_id',Auth::user()->id)
                    ->leftJoin('dev_author','dev_author.id','=','dev_like.like_id')
                    ->select('dev_like.*','dev_author.author_name','dev_author.dynasty','dev_author.like_count','dev_author.collect_count')
                    ->orderBy('dev_like.id','desc')
                    ->paginate(10);
            }elseif (isset($type) && $type == 'sentence'){
                $c_sentences = DB::table('dev_like')
                    ->where('dev_like.type','sentence')
                    ->where('dev_like.status','active')
                    ->where('dev_like.user_id',Auth::user()->id)
                    ->leftJoin('dev_sentence','dev_sentence.id','=','dev_like.like_id')
                    ->leftJoin('dev_poem','dev_poem.source_id','=','dev_sentence.target_source_id')
                    ->select('dev_like.*','dev_sentence.title','dev_sentence.like_count','dev_sentence.collect_count','dev_sentence.content','dev_poem.id as poem_id','dev_poem.title as poem_title','dev_poem.author')
                    ->orderBy('dev_like.id','desc')
                    ->paginate(20);
            }else{
                $c_poems = DB::table('dev_like')
                    ->where('dev_like.type','poem')
                    ->where('dev_like.status','active')
                    ->where('dev_like.user_id',Auth::user()->id)
                    ->leftJoin('dev_poem','dev_poem.id','=','dev_like.like_id')
                    ->select('dev_like.*','dev_poem.title','dev_poem.author','dev_poem.dynasty','dev_poem.like_count','dev_poem.collect_count','dev_poem.content')
                    ->orderBy('dev_like.id','desc')
                    ->paginate(10);
            }
            $p_count = $this->getLikeCount(Auth::user()->id,'poem');
            $a_count = $this->getLikeCount(Auth::user()->id,'author');
            $m_count = $this->getLikeCount(Auth::user()->id,'sentence');
            return view('people.like')
                ->with('poems',$c_poems)
                ->with('query','collect')
                ->with('authors',$c_authors)
                ->with('sentences',$c_sentences)
                ->with('a_count',$a_count)
                ->with('m_count',$m_count)
                ->with('type',$type)
                ->with($this->getClAndLkCount())
                ->with('site_title','我的喜欢')
                ->with('p_count',$p_count);
        }
    }

    /**
     * @param $user_id
     * @param $type
     * @return mixed
     */
    public function getLikeCount($user_id,$type){
       $count = DB::table('dev_like')
            ->where('type',$type)
            ->where('status','active')
            ->where('user_id',$user_id)
            ->count();
       return $count;
    }
}