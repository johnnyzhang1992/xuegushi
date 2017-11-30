<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
use Validator;
use Datatables;

/**
 * Class DashboardController
 * @package App\Http\Controllers
 */
class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return mixed
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return mixed
     */
    public function index()
    {
        $count = array();
        $count['users'] = DB::table('users')->count();
        $count['poems'] = DB::table('dev_poem')->count();
        $count['authors'] = DB::table('dev_author')->count();
        $count['dynasty'] = DB::table('poem_dynasty')->count();
        $count['type'] = DB::table('poem_type')->count();
        return view('la.dashboard')
            ->with('count',$count);
    }
}