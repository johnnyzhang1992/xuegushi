<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers;

use DB;
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
        return view('home')
            ->with('query','home')
            ->with('poems',$poems);
    }
}