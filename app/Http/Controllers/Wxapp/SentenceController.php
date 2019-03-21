<?php

namespace App\Http\Controllers\Wxapp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Cache;
use Carbon;
use DB;
use Log;
use Hash;
use Auth;
use Config;
use \stdClass;
//use phpDocumentor\Reflection\Types\Array_;


class SentenceController extends Controller {
    /**
     * 验证微信token的有效性
     * @param $token
     * @param $u_id
     * @return boolean
     */
    public function validateWxToken($u_id,$token){
        $user = DB::table('users')
            ->where('id',$u_id)
            ->where('wx_token',trim($token))
            ->first();
        if(isset($user) && $user){
            // 验证通过
            return true;
        }else{
            return false;
        }
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
        $_keyWord = $request->input('keyWord');
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
        if(isset($_keyWord) && $_keyWord){
            $_sentences ->where('dev_sentence.title','like','%'.$_keyWord.'%');
        }
        $_sentences->orderBy('dev_sentence.like_count','desc');
        $_sentences->leftJoin('dev_poem','dev_poem.source_id','=','dev_sentence.target_source_id');
        $_sentences->select('dev_sentence.*','dev_poem.id as poem_id','dev_poem.author','dev_poem.dynasty','dev_poem.title as poem_title','dev_poem.tags');
        $_sentences = $_sentences->paginate(8)->setPath($_url);

        $res = [];
        $res['poems'] = $_sentences;
        $res['type'] = $type;
        $res['theme'] = $theme;
        $res['themes'] = $themes;
        $res['types'] = $types;

        return response()->json($res);
    }
    /**
     * poem 详情页
     * @param $id
     * @param $request
     * @return mixed
     */
    public function getSentenceDetail(Request $request,$id){
        $user_id = $request->input('user_id');
        $author = null;
        // sentence 相关
        $sentence = DB::table('dev_sentence')
            ->where('id',$id)
            ->select('id','source_id','target_source_id','title','theme','type','origin')
            ->first();
        DB::table('dev_sentence')->where('id',$id)->increment("pv_count");
        if(!isset($sentence) || !$sentence){
            return response()->json([
                'status' => 404,
                'message' => '句子不存在'
            ]);
        }
        $sentence->key = $sentence->title;
        // 相关联的 poem
        $poem = DB::table('dev_poem')
            ->where('source_id',$sentence->target_source_id)
            ->select('dynasty','id','source_id','title','content','author','author_source_id')
            ->first();
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
        $poem->content = mb_substr($content,0,50,'utf-8');
        if($poem){
            if($poem->author != '佚名'){
                $author = DB::table('dev_author')
                    ->where('source_id',$poem->author_source_id)
                    ->select('id','dynasty','author_name','profile','source_id')
                    ->first();
                if(file_exists('static/author/'.@$author->author_name.'.jpg')){
                    $author->avatar = asset('static/author/'.@$author->author_name.'.jpg');
                }
            }else{
//                $poem->status = 'delete';
                $poem->author_id = -1;
            }
        }
        if($user_id == 0){
            $sentence->collect_status = false;
        }else{
            $collect = DB::table('dev_collect')
                ->where('user_id',$user_id)
                ->where('like_id',$sentence->id)
                ->where('type','sentence')
                ->first();
            if(isset($collect) && $collect->status == 'active'){
                $sentence->collect_status = true;
            }else{
                $sentence->collect_status = false;
            }
        }
        $res = [];
        $res['sentence'] = $sentence;
        $res['author'] = $author;
        $res['poem'] = $poem;
        $res['bg_image'] = 'https://xuegushi.cn/static/xcx/chunfen.jpg';
        return response()->json($res);
    }

}