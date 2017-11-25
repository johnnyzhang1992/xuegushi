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
class PageController extends Controller
{
    protected $query = null;
    /**
     * Create a new controller instance.
     *
     * @return mixed
     */
    public function __construct()
    {
//        $this->query = 'poems';
    }

    /**
     * 联系我们
     */
    public function contact(){
        return view('frontend.page.contact')
            ->with('site_title','联系我们');
    }
    /**
     * 加入我们
     */
    public function join(){
        return view('frontend.page.join_us')
            ->with('site_title','加入我们');
    }
    /**
     * 免责声明
     */
    public function about(){
        return view('frontend.page.about')
            ->with('site_title','免责声明');
    }
}