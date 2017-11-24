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
        $author = DB::table('author')->where('id',$id)->first();
        if($author){
            $author_detail = DB::table('author_detail')->where('author_id',$id)->first();
            if($author->author != '佚名'){
                $author = DB::table('author')->where('author_name',$author->author)->first();
            }
            return view('frontend.author.show')
                ->with('query','authors')
                ->with('author',$author)
                ->with('detail',$author_detail)
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