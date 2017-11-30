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
        $poems = DB::table('dev_poem')
            ->where('like_count','>',100)
            ->orderBy('like_count','desc')
            ->paginate(10);
        if(!Auth::guest()){
            foreach ($poems as $poem){
                $res = DB::table('dev_like')
                    ->where('user_id',Auth::user()->id)
                    ->where('like_id',$poem->id)
                    ->where('type','poem')->first();
                if(isset($res) && $res->status == 'active'){
                    $poem->status = 'active';
                }else{
                    $poem->status = 'delete';
                }
            }
        }
        return view('home')
            ->with('query','home')
            ->with('poems',$poems);
    }
}