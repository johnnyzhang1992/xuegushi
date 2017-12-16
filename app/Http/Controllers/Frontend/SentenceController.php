<?php
/**
 * Controller Mingju
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
class SentenceController extends Controller
{
    protected $query = null;

    /**
     * Create a new controller instance.
     *
     * @return mixed
     */
    public function __construct()
    {
        $this->query = 'sentence';
    }

    /**
     * sentence 首页
     * @param $request
     * @return mixed
     */
    public function index(Request $request){
        $type = $request->input('type');
        $theme = $request->input('theme');
        $themes = config('sentence.themes');
        $_url = 'sentence?';
        $_sentences = DB::table('dev_sentence');
        $types = array();
        $site_title = '';
        if($theme){
            if($theme != 'all'){
                $_sentences->where('dev_sentence.theme','like','%'.$theme.'%');
                $types = $this->getThemeTypes($theme);
                $site_title = '主题为'.$theme;
            }
            $_url = $_url.'theme='.$theme;
        }
        if($type){
            if($type != 'all'){
                $_sentences->where('dev_sentence.type','like','%'.$type.'%');
                $site_title = $site_title.'_类型为'.$type.'的';
            }
            $_url = $_url.'&type='.$type;
        }
        $_sentences->orderBy('dev_sentence.like_count','desc');
        $_sentences->leftJoin('dev_poem','dev_poem.source_id','=','dev_sentence.target_source_id');
        $_sentences->select('dev_sentence.*','dev_poem.id as poem_id','dev_poem.author','dev_poem.dynasty','dev_poem.title as poem_title','dev_poem.tags');
        $_sentences = $_sentences->paginate(20)->setPath($_url);
        if(!Auth::guest()){
            foreach ($_sentences as $sentence){
                $res = DB::table('dev_like')
                    ->where('user_id',Auth::user()->id)
                    ->where('like_id',$sentence->id)
                    ->where('type','sentence')->first();
                if(isset($res) && $res->status == 'active'){
                    $sentence->status = 'active';
                }
                $collect = DB::table('dev_collect')
                    ->where('user_id',Auth::user()->id)
                    ->where('like_id',$sentence->id)
                    ->where('type','sentence')->first();
                if(isset($collect) && $collect->status == 'active'){
                    $sentence->collect_status = 'active';
                }
            }
        }
        return view('frontend.sentence.index')
            ->with('query','sentence')
            ->with('site_title',$site_title.'名句')
            ->with('type',$type)
            ->with('types',$types)
            ->with('theme',$theme)
            ->with('themes',$themes)
            ->with($this->getClAndLkCount())
            ->with('h_authors',$this->getHotAuthors())
            ->with('sentences',$_sentences);
    }

    /**
     * 获取某个主题的类型
     * @param null $theme
     * @return array
     */
    public function getThemeTypes($theme=null){
        $theme = $theme ? $theme : '抒情';
        $types = config('sentence.types');
        $_types = array();
        foreach ($types as $type){
            if($type['theme_name'] == $theme){
              $_types =  $type['types'];
            }
        }
        return $_types;
    }
}