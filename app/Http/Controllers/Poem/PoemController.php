<?php
namespace App\Http\Controllers\Poem;

use DB;
use Config;
use Exception;
//    use Illuminate\Support\Facades\Session;
use Session;
use \stdClass;
use DateTime;
use Log;
use Redirect;
use Cache;
use Carbon;
use Storage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PoemController extends Controller
{

    /**
     * Constructor
     */
//    public function __construct()
//    {
//        parent::__construct();
//    }

    /**
     * @return mixed
     */
    public function index(){
        $poem = DB::table('poem')
            ->where('poem.id','=','67680')
            ->first();
        $_poem = [];
        $_poem['count'] = $poem;
//        foreach ($poem as $item){
//
//            $_con = json_decode($item->content);
//            if(isset($_con) && isset($_con->content)){
//                $_con1 = array();
//                foreach ($_con->content as $k=>$it){
//                    $it = $this->unicode_decode($it);
//                    $_con1[$k] = $it;
//                }
//                $_poem['content'] = $_con1;
//                $_content = array();
//                $_content['content'] = $_poem['content'];
////            $_poem['con1'] = $_content;
//                DB::table('poem')->where('id',$item->id)->update([
//                    'content'=> json_encode($_content)
//                ]);
//            }

//            if(isset($_tags)){
//                DB::table('poem')->where('id',$item->id)->update([
//                    'tags'=> json_encode($_tags)
//                ]);
//            }
//        }
//        $_poem['title'] = $this->unicode_decode($poem->title);
//        $_poem['aaaa'] = $poem;

//        foreach ($_poem['content'] as $k=>$item){
//            if($k ==1){
//                $_poem['content'][$k] = '当此际，意偏长。萋萋芳草傍池塘。千钟尚欲偕春醉，幸有荼蘼与海棠。';
//            }
//        }

//        $_poem['test'] = $this->unicode_decode('\u7b2c\u4e00\u6298');
//        $_poem['tags'] = json_decode($poem->tags);
        print_r($_poem);
//        return response()->json($_poem);
    }
    public function author(){
        $author = DB::table('author')->where('id',307)->first();

        print_r(json_decode($author->more_infos));
    }
    function unicode_decode($name){

        $json = '{"str":"'.$name.'"}';
        $arr = json_decode($json,true);
        if(empty($arr)) return '';
        return $arr['str'];
    }
    function unicode_to_utf8($unicode_str) {
        $utf8_str = '';
        $code = intval(hexdec($unicode_str));
        //这里注意转换出来的code一定得是整形，这样才会正确的按位操作
        $ord_1 = decbin(0xe0 | ($code >> 12));
        $ord_2 = decbin(0x80 | (($code >> 6) & 0x3f));
        $ord_3 = decbin(0x80 | ($code & 0x3f));
        $utf8_str = chr(bindec($ord_1)) . chr(bindec($ord_2)) . chr(bindec($ord_3));
        return $utf8_str;
    }
    



}