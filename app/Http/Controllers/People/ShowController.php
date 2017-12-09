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
//        $this->middleware('auth');
    }
    public function index(Request $request){
//        $request->setTrustedProxies(array('10.32.0.1/16'));
        $ip = request()->ip();
        return view('people.show')
            ->with('ip',$ip)
            ->with($this->getClAndLkCount())
            ->with('h_authors',$this->getHotAuthors());
    }
    /**
     * 更新所有用户的like collect数量
     */
    public function updateLikeCollect(){
        $users = DB::table('users')->whereNull('deleted_at')->get();
        foreach ($users as $user){
            $like_count = DB::table('dev_like')->where('user_id',$user->id)->where('status','active')->count();
            $collect_count = DB::table('dev_collect')->where('user_id',$user->id)->where('status','active')->count();
            DB::table('users')
                ->where('id',$user->id)
                ->update([
                    'like_count' => $like_count,
                    'collect_count' =>$collect_count
                ]);
        }
    }
}