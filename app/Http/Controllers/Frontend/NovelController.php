<?php

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
class NovelController extends Controller
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

    public function index(){
        $books = DB::table('dev_book_list')
            ->get();
        return view('frontend.novel.index')
            ->with('site_title','小说列表')
            ->with('lists',$books);
    }
    /**
     * 小说详情
     * @param $id
     * @return mixed
     */
    public function show($id){
        $data = DB::table('dev_book')
            ->where('book_id',$id)
            ->select('id','title','book_name','book_id')
            ->orderBy('id','asc')
            ->paginate(20);
        $detail = DB::table('dev_book_list')
            ->where('id',$id)
            ->first();
        if(isset($data) && $data){
            return view('frontend.novel.show')
                ->with('site_title',$detail->name)
                ->with('novel',$detail)
                ->with('lists',$data);
        }else{
            return view('errors.404')
                ->with('record_id',$id)
                ->with('record_name','小说');
        }
    }

    /**
     * 章节详情
     * @param $id
     * @param $chapter_id
     * @return mixed
     */
    public function detail($id,$chapter_id){
        $detail = DB::table('dev_book')
            ->where('book_id',$id)
            ->where('id',$chapter_id)
            ->first();
        return view('frontend.novel.detail')
            ->with('site_title',$detail->title)
            ->with('detail',$detail);
    }
}