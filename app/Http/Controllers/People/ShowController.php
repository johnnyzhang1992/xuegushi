<?php
/**
 * Controller show
 * 用户前端主页controller
 */

namespace App\Http\Controllers\People;

use DB;
use Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class ShowController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request){
//        $request->setTrustedProxies(array('10.32.0.1/16'));
        $ip = request()->ip();
        return view('people.show')
            ->with('ip',$ip)
            ->with($this->getClAndLkCount())
            ->with('h_authors',$this->getHotAuthors());
    }
}