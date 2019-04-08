<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Http\Requests;
use Illuminate\Http\Request;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     * @return Response
     */
    public function index(){
        // 获取随机数组

        $poems = DB::table('dev_poem')
//            ->where('like_count','>',100)
//            ->where('type','!=','文言文')
            ->whereIn('id',$this->numberRand(1,72439,6))
            ->orderBy('like_count','desc')
            ->get();
        if(!Auth::guest()){
            foreach ($poems as $poem){
                $like = DB::table('dev_like')
                    ->where('user_id',Auth::user()->id)
                    ->where('like_id',$poem->id)
                    ->where('type','poem')->first();
                $collect = DB::table('dev_collect')
                    ->where('user_id',Auth::user()->id)
                    ->where('like_id',$poem->id)
                    ->where('type','poem')->first();
                if($poem->author_source_id != -1){
                    $author = DB::table('dev_author')->where('source_id',$poem->author_source_id)->first();
                    $poem->author_id = $author->id;
                }else{
                    $poem->author_id = -1;
                }
                if(isset($like) && $like->status == 'active'){
                    $poem->status = 'active';
                }else{
                    $poem->status = 'delete';
                }
                if(isset($collect) && $collect->status == 'active'){
                    $poem->collect_status = 'active';
                }else{
                    $poem->collect_status = 'delete';
                }
            }
        }
        return view('home')
            ->with('query','home')
            ->with($this->getClAndLkCount())
            ->with('h_authors',$this->getHotAuthors())
            ->with('poems',$poems);
    }
    public function numberRand($begin = 1, $end = 72439, $limit = 10){
        $rand_array = range($begin, $end);
        shuffle($rand_array); //调用现成的数组随机排列函数
        return array_slice($rand_array, 0, $limit); //截取前$limit个
    }
}