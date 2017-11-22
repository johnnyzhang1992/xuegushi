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
    protected $query = null;
    /**
     * Create a new controller instance.
     *
     * @return mixed
     */
    public function __construct()
    {
        $this->query = 'poems';
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
     * poem 详情页
     * @param $id
     * @return mixed
     */
    public function show($id){
        $author = null;
        $poem = DB::table('poem')->where('id',$id)->first();
        if($poem){
            $poem_detail = DB::table('poem_detail')->where('poem_id',$id)->first();
            if($poem->author != '佚名'){
                $author = DB::table('author')->where('author_name',$poem->author)->first();
            }
            return view('frontend.poem.show')
                ->with('query','poems')
                ->with('author',$author)
                ->with('detail',$poem_detail)
                ->with('poem',$poem);
        }else{
            return view('errors.404');
        }
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