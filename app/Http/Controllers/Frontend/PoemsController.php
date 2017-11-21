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
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * poems index page
     * @return mixed
     */
    public function index()
    {
        $poems = DB::table('poem')
            ->orderBy('like_count','desc')
            ->paginate(10);
        return view('frontend.poem.index')
            ->with('query','poems')
            ->with('poems',$poems);
    }

    /**
     * update poem database
     */
    public function updatePoemLikeCount(){
        $poems = DB::table('poem_detail')->get();
        foreach ($poems as $key=>$poem){
            $res = DB::table('poem')->where('id',$poem->poem_id)->update(['type'=>$poem->type]);
            if(!$res){
                break;
            }
            if($key+1 == count($poems)){
                print('ok!');
            }
        }
    }

}