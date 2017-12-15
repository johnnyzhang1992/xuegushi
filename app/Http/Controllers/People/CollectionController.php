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

    /**
     * @param null $_type
     * @return mixed
     */
    public function index($_type=null){
        $type = $_type;
        $c_authors = array();
        $m_sentences = array();
        $c_poems = array();
        if(!Auth::guest()){
            if(isset($type) && $type =='author'){
                $c_authors = DB::table('dev_collect')
                    ->where('dev_collect.type','author')
                    ->where('dev_collect.status','active')
                    ->where('dev_collect.user_id',Auth::user()->id)
                    ->leftJoin('dev_author','dev_author.id','=','dev_collect.like_id')
                    ->select('dev_collect.*','dev_author.author_name','dev_author.dynasty','dev_author.like_count','dev_author.collect_count')
                    ->orderBy('dev_collect.id','desc')
                    ->paginate(10);
            }elseif (isset($type) && $type == 'sentence'){
                $m_sentences = DB::table('dev_collect')
                    ->where('dev_collect.type','sentence')
                    ->where('dev_collect.status','active')
                    ->where('dev_collect.user_id',Auth::user()->id)
                    ->leftJoin('dev_sentence','dev_sentence.id','=','dev_collect.like_id')
                    ->leftJoin('dev_poem','dev_poem.source_id','=','dev_sentence.target_source_id')
                    ->select('dev_collect.*','dev_sentence.title','dev_sentence.like_count','dev_sentence.collect_count','dev_sentence.content','dev_poem.id as poem_id','dev_poem.title as poem_title','dev_poem.author')
                    ->orderBy('dev_collect.id','desc')
                    ->paginate(20);
            }else{
                $c_poems = DB::table('dev_collect')
                    ->where('dev_collect.type','poem')
                    ->where('dev_collect.status','active')
                    ->where('dev_collect.user_id',Auth::user()->id)
                    ->leftJoin('dev_poem','dev_poem.id','=','dev_collect.like_id')
                    ->select('dev_collect.*','dev_poem.title','dev_poem.author','dev_poem.dynasty','dev_poem.like_count','dev_poem.collect_count','dev_poem.content')
                    ->orderBy('dev_collect.id','desc')
                    ->paginate(10);
            }
            $p_count = $this->getCollectCount(Auth::user()->id,'poem');
            $a_count = $this->getCollectCount(Auth::user()->id,'author');
            $m_count = $this->getCollectCount(Auth::user()->id,'sentence');
            return view('people.collect')
                ->with('poems',$c_poems)
                ->with('query','collect')
                ->with('authors',$c_authors)
                ->with('sentences',$m_sentences)
                ->with('a_count',$a_count)
                ->with('m_count',$m_count)
                ->with('p_count',$p_count)
                ->with('type',$type)
                ->with($this->getClAndLkCount())
                ->with('site_title','我的收藏');
        }
    }

    /**
     * @param $user_id
     * @param $type
     * @return mixed
     */
    public function getCollectCount($user_id,$type){
        $count = DB::table('dev_collect')
            ->where('type',$type)
            ->where('status','active')
            ->where('user_id',$user_id)
            ->count();
        return $count;
    }
}