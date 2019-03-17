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


class SearchController extends Controller {

    /**
     * 搜索功能
     * @param $_key
     * @return mixed
     */
    public function getSearchResult($_key){
        $_key = trim($_key);
        $this->searchRecord($_key);
        $authors = DB::table('dev_author')
            ->where('author_name','like','%'.$_key.'%')
            ->select('id','dynasty','author_name','like_count','profile')
            ->orderBy('like_count','desc')
            ->paginate(3);
        $tags = DB::table('dev_poem')
            ->where('tags','like','%'.$_key.'%')
            ->select('tags')
            ->paginate(3);
        $_tags = array();
        foreach ($tags as $tag){
            foreach(explode(',',$tag->tags) as $_tag){
                if(strpos($_tag,$_key)!== false){
                    array_push($_tags,$_tag);
                }
            }
        }
        $_tags = array_unique($_tags);
        $sentences = DB::table('dev_sentence')
            ->where('dev_sentence.title','like','%'.$_key.'%')
            ->leftJoin('dev_poem','dev_poem.source_id','=','dev_sentence.target_source_id')
            ->select('dev_sentence.title','dev_sentence.id','dev_poem.id as poem_id','dev_poem.author','dev_poem.dynasty','dev_poem.title as poem_title')
            ->paginate(3);
        $poems = DB::table('dev_poem')
            ->where('title','like','%'.$_key.'%')
            ->orWhere('text_content','like','%'.$_key.'%')
            ->select('id','title','author','dynasty','like_count','content')
            ->orderBy('like_count','desc')
            ->paginate(3);
        foreach ($sentences as $key=>$_sentence){
            $sentences[$key]->name = $_sentence->title;
            $sentences[$key]->key =$_key;
        }
        foreach ($authors as $key=>$_author){
            $authors[$key]->name = $_author->author_name;
            $authors[$key]->key = $_key;
        }
        foreach ($poems as $key=>$poem){
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
            $poems[$key]->content = mb_substr($content,0,35,'utf-8');
            $poems[$key]->key = $_key;
            $poems[$key]->name = $poem->title;
            $poems[$key]->type = 'poem';
        }
        return response()->json([
            'tags' => $_tags,
            'sentences' => $sentences,
            'poems' => $poems,
            'poets' =>$authors
        ]);
    }

    /**
     * 记录搜索记录
     * @param $key
     */
    public function searchRecord($key){
        $today = date('Y-m-d',time()).' 00:00:00';
        $_result = DB::table('dev_search')
            ->where('name',$key)
            ->where('created_at','>',$today)
            ->first();
        if(isset($_result)&& $_result){
            DB::table('dev_search')->where('id',$_result->id)->increment("count");
        }else{
            DB::table('dev_search')->insertGetId([
                'name' =>trim($key),
                'status'=>'active',
                'created_at' =>date('Y-m-d H:i:s',time())
            ]);
        }
    }

    /**
     * 获取热门搜索词
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHotSearchWord(){
        $today = date('Y-m-d',time()).' 00:00:00';
        $_result = DB::table('dev_search')
            ->where('status','active')
            ->where('created_at','>',$today)
            ->orderBy('count','desc')
            ->select('name')
            ->limit(8)
            ->get();
        $cur_length = count($_result);
        $week_time = date('Y-m-d H:i:s', strtotime($today .' -3 day'));
        if($cur_length<8){
            $limit = 8 - $cur_length;
            // 获取最近三天搜索量最多的词
            $_result1 = DB::table('dev_search')
                ->where('status','active')
                ->where('created_at','>',$week_time)
                ->where('created_at','<',$today)
                ->orderBy('count','desc')
                ->select('name')
                ->limit($limit)
                ->get();
            $_result = array_merge($_result,$_result1);
        }
        $_lists = array();
        foreach ($_result as $_list){
            array_push($_lists,mb_substr($_list->name,0,8,"UTF-8"));
        }
        return response()->json($_lists);
    }
    /**
     * 搜索列表
     * @param $request
     * @return mixed
     */
    public function getSearchList(Request $request){
        $list = DB::table('dev_search')
            ->where('status','active')
            ->orderBy('id','desc')
            ->paginate(10);
        return response()->json($list);
    }
    /**
     * 搜索内容状态更新
     * @param $id
     * @return mixed
     */

    public function searchUpdate($id){
        $item = DB::table('dev_search')->where('id',$id)->first();
        $res = [];
        if(isset($item) && $item){
            $result = DB::table('dev_search')->where('id',$id)->update([
                'status'=>'delete'
            ]);
            $item = DB::table('dev_search')->where('id',$id)->first();
            if($result){
                $res['status'] = 200;
                $res['message'] = 'success';
                $res['data'] = $item;
            }
        }else{
            $res['status'] = 500;
            $res['message'] = '项目不存在';
        }
        return response()->json($res);
    }

}