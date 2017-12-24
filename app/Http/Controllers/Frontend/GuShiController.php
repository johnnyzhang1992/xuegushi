<?php
/**
 * Controller Poems
 */

namespace App\Http\Controllers\Frontend;

use DB;
use Auth;
use BaiduSpeech;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class GuShiController extends Controller
{
    protected $query = null;

    /**
     * Create a new controller instance.
     *
     * @return mixed
     */
    public function __construct()
    {
        $this->query = 'poems';
    }

    /**
     * @param $name
     * @return mixed
     */
    public function index($name){
        $data = null;
        $title = null;
        $books = null;
        switch ($name){
            case 'songci':
                $title = '宋词精选';
                $data = $this->getData($title);
                break;
            case 'shijiu':
                $title = '古诗十九首';
                $data = $this->getData($title);
                break;
            case 'xiaoxuewyw':
                $title = '小学文言文';
                $data = $this->getData($title);
                break;
            case 'chuci':
                $title = '楚辞';
                $data = $this->getData($title);
                break;
            case 'tangshi':
                $title = '唐诗三百首';
                $books = $this->getBooks($name);
                $data = $this->getAllData($name);
                break;
            case 'sanbai':
                $title = '古诗三百首';
                $books = $this->getBooks($name);
                $data = $this->getAllData($name);
                break;
            case 'songcisanbai':
                $title = '宋词三百首';
                $books = $this->getBooks($name);
                $data = $this->getAllData($name);
                break;
            case 'shijing':
                $title = '诗经';
                $books = $this->getBooks($name);
                $data = $this->getAllData($name);
                break;
            case 'yuefu':
                $title = '乐府';
                $books = $this->getBooks($name);
                $data = $this->getAllData($name);
                break;
            case 'xiaoxue':
                $title = '小学古诗';
                $books = $this->getBooks($name);
                $data = $this->getAllData($name);
                break;
            case 'chuzhong':
                $title = '初中古诗';
                $books = $this->getBooks($name);
                $data = $this->getAllData($name);
                break;
            case 'chuzhongwyw':
                $title = '初中文言文';
                $books = $this->getBooks($name);
                $data = $this->getAllData($name);
                break;
            case 'gaozhong':
                $title = '高中古诗';
                $books = $this->getBooks($name);
                $data = $this->getAllData($name);
                break;
            case 'gaozhongwyw':
                $title = '高中文言文';
                $books = $this->getBooks($name);
                $data = $this->getAllData($name);
                break;
            default:
                $title = '古诗三百首';
                $data = $this->getData($title);
                break;
        }
        return view('frontend.poem.gushi')
            ->with('query',$this->query)
            ->with('site_title',$title)
            ->with('title',$title)
            ->with('books',$books)
            ->with('data',$data);
    }
    /**
     * @param $name
     * @return mixed
     */
    public function getData($name){
        $value = '%'.$name.'%';
        $data = DB::table('dev_poem')
            ->where('tags','like',$value)
//            ->where('like_count','>',10)
            ->orderBy('like_count','desc')
            ->get();
        return $data;
    }
    public function getAllData($name){
        $data = DB::table('dev_poem_book')
            ->where('dev_poem_book.belong_name','=',$name)
            ->leftJoin('dev_poem','dev_poem.source_id','=','dev_poem_book.source_id')
            ->select('dev_poem.id','dev_poem.author','dev_poem.title','dev_poem_book.belong_name','dev_poem_book.book')
            ->get();
        return $data;
    }
    public function getBooks($name){
        $data = DB::table('dev_poem_book')
            ->where('belong_name',$name)
            ->orderBy('id')
            ->select('book')
            ->get();
        $_arr = array();
        foreach ($data as $item){
            array_push($_arr,$item->book);
        }
        $_arr = array_unique($_arr);
//        sort($_arr);
        return $_arr;
    }
}