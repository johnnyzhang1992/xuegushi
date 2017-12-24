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
use App\Helpers\DateUtil;

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
    /**
     * 专题页面展示
     * @param $id
     * @return mixed
     */
    public function show($id){
        $data = null;
        info(gettype($id));
        $data = DB::table('dev_pages')
            ->where('id',$id)
            ->first();
        if(!isset($data)){
            $data = DB::table('dev_pages')
                ->where('name',$id)
                ->first();
        }
        if(isset($data) && $data){
            $data->created_at = DateUtil::formatDate(strtotime($data->created_at));
            return view('frontend.page.show')
                ->with('page',$data)
                ->with('site_title',@$data->display_name);
        }else{
            return view('errors.404')
                ->with('record_id',$id)
                ->with('record_name','专题页');
        }
    }
}