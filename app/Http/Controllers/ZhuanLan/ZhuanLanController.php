<?php
/**
 * Controller show
 * 用户前端主页controller
 */

namespace App\Http\Controllers\ZhuanLan;

use DB;
use Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class ZhuanLanController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth');
    }
    public function index(){
        print 'hello world';
    }
}