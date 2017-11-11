<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use DB;
use Validator;
use Datatables;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;

class PoemsController extends Controller
{
    public $show_action = true;
    public $view_col = 'name';
    public $listing_cols = ['id','title','dynasty','author','type','like_count','tags'];

    public function __construct() {
        // Field Access of Listing Columns
        if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
            $this->middleware(function ($request, $next) {
                $this->listing_cols = ['id','title','dynasty','author','type','like_count','tags'];
                return $next($request);
            });
        } else {
            $this->listing_cols = ['id','title','dynasty','author','type','like_count','tags'];
        }
    }

    /**
     * Display a listing of the Poems.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $module = array();
        $custom_menu = array();
        $custom_menu['name'] = 'poems';
        $custom_menu['id'] = '9';

        return View('la.poem.index', [
                'show_actions' =>$this->show_action,
                'listing_cols' => $this->listing_cols,
                'module' => $module,
                'custom_menu'=>$custom_menu
            ]);
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return mixed
     **/
    public function show($id){
        $custom_menu = array();
        $custom_menu['name'] = 'poems';
        $custom_menu['id'] = '9';
        $poem = DB::table('poem')->where('id',$id)->first();
        $poem_content = json_decode($poem->content);
        $poem_detail = null;
        $detail = DB::table('poem_detail')->where('poem_id',$id)->first();
        if(isset($detail) && isset($detail->id)){
            $poem_detail = $detail;
        }
        return View('la.poem.show')
            ->with('poem',$poem)
            ->with('poem_content',$poem_content)
            ->with('detail',$poem_detail)
            ->with('custom_menu',$custom_menu);
    }

    /**
     * 编辑
     * @param $id
     * @return mixed
     */
    public function edit($id){
        $custom_menu = array();
        $custom_menu['name'] = 'poems';
        $custom_menu['id'] = '9';
        $poem = DB::table('poem')->where('id',$id)->first();
        $poem_content = json_decode($poem->content);
        $poem_detail = null;
        $detail = DB::table('poem_detail')->where('poem_id',$id)->first();
        if(isset($detail) && isset($detail->id)){
            $poem_detail = $detail;
        }
        return View('la.poem.edit')
            ->with('poem',$poem)
            ->with('poem_content',$poem_content)
            ->with('detail',$poem_detail)
            ->with('custom_menu',$custom_menu);
    }
    /**
     * 保存
     * @param $request
     * @return mixed
     */
    public function store(Request $request){
        $type = $request->input('type1');
        info($type);
        $_data = array();
        if(isset($type) && $type == 'normal'){
            $id = $request->input('id');
            info('---id---:'.$id);
            $_tags = $request->input('tags');
            $poem = array();
            $poem['title'] = $request->input('title');
            $poem['dynasty'] = $request->input('dynasty');
            $poem['author'] = $request->input('author');
            $poem['tags'] = json_encode(explode(',',$_tags));
            $poem['background'] = $request->input('background');
            $poem['content'] = json_encode($request->input('con'));
            $poem['updated_at'] =date('Y-m-d H:i:s',time());
            $res = DB::table('poem')->where('id',$id)->update($poem);
            if($res){
                $_data['msg'] = 'success';
                return response()->json($_data,200);
            }else{
                $_data = array();
                $_data['msg'] = 'error';
                return response()->json($_data,500);
            }
        }elseif(isset($type) && $type == 'detail'){
            $id = $request->input('id');
            info('---detail-id---:'.$id);
            $detail_con = $request->input('detail_con');
            $yi = $request->input('yi');
            $zhu = $request->input('zhu');
            $shang = $request->input('shang');
            $res = DB::table('poem_detail')
                ->where('id',$id)
                ->update([
                    'poem_title' => $request->input('title'),
                    'type' => $request->input('type'),
                    'updated_at' =>date('Y-m-d H:i:s',time()),
                    'content' => json_encode($detail_con),
                    'yi' => json_encode($yi),
                    'zhu' => json_encode($zhu),
                    'shangxi' => json_encode($shang),
                    'more_infos' => json_encode($request->input('more_info'))
                ]);
            if($res){
                $_data['msg'] = 'success';
                return response()->json($_data,200);
            }else{
                $_data = array();
                $_data['msg'] = 'error';
                return response()->json($_data,500);
            }
        }
    }
    /**
     * ajax
     * @param $id
     * @param $request
     * @return mixed
     */
    public function ajax($id,Request $request){
        $value = $request->input('val');
        $item = null;
        if(isset($value) && $id>0){
            switch ($value){
                case 'yi':
                    $item = $this->getYi($id);
                    break;
                case 'zhu':
                    $item = $this->getZhu($id);
                    break;
                case 'shangxi':
                    $item = $this->getShangxi($id);
                    break;
                default:
                    $item = $this->getContent($id);
                    break;
            }
            return response()->json($item,200);
        } else{
            return response()->json($item,500);
        }
    }
    /**
     * 正文
     * @param $id
     * @return mixed
     */
    public function getContent($id){
        $poem_detail = DB::table('poem_detail')->where('poem_id',$id)->first();
        $item = array();
        if($poem_detail){
            if(isset($poem_detail->content) && $poem_detail->content) {
                if(isset(json_decode($poem_detail->content)->xu) && json_decode($poem_detail->content)->xu){
                    $item['xu'] = json_decode($poem_detail->content)->xu;
                }
                $item['content'] = json_decode($poem_detail->content)->content;
            }
        }else{
            $poem = DB::table('poem')->where('id',$id)->first();
            if(isset($poem->content) && $poem->content){
                $poem_content = json_decode($poem->content);
                if(isset($poem_content->xu) && $poem_detail->xu){
                    $item['xu'] = $poem_content->xu;
                }
                $item['content'] = $poem_content->content;
            }
        }
        return $item;
    }
    /**
     * 翻译
     * @param $id
     * @return mixed
     */
    public function getYi($id){
        $poem_detail = DB::table('poem_detail')->where('poem_id',$id)->first();
        $item = array();
        if($poem_detail){
            if(isset($poem_detail->yi) && $poem_detail->yi) {
                $item['reference'] = json_decode($poem_detail->yi)->reference;
                $item['content'] = json_decode($poem_detail->yi)->content;
            }
        }
        return $item;
    }
    /**
     * 注释
     *  @param $id
     * @return mixed
     */

    public function getZhu($id){
        $poem_detail = DB::table('poem_detail')->where('poem_id',$id)->first();
        $item = array();
        if($poem_detail){
            if(isset($poem_detail->zhu) && $poem_detail->zhu) {
                $item['reference'] = json_decode($poem_detail->zhu)->reference;
                $item['content'] = json_decode($poem_detail->zhu)->content;
            }
        }
        return $item;
    }
    /**
     * 赏析
     * @param $id
     * @return mixed
     */
    public function getShangxi($id){
        $poem_detail = DB::table('poem_detail')->where('poem_id',$id)->first();
        $item = array();
        if($poem_detail){
            if(isset($poem_detail->shangxi) && $poem_detail->shangxi){
                $item['reference'] = json_decode($poem_detail->shangxi)->reference;
                $item['content'] = json_decode($poem_detail->shangxi)->content;
            }
        }
        return $item;
    }
    /**
     * Datatable Ajax fetch
     *
     * @return mixed
     */
    public function dtajax()
    {
        $values = DB::table('poem')
            ->select('poem.id','poem.title','poem.dynasty','poem.author','poem_detail.type','poem_detail.like_count','poem.tags')
            ->leftJoin('poem_detail','poem_detail.poem_id','=','poem.id');
        $out = Datatables::of($values)->make();
        $data = $out->getData();

        for($i=0; $i < count($data->data); $i++) {
            $data->data[$i][6] = json_decode($data->data[$i][6] );
            if($this->show_action) {
                $output = '';
                $output .= '<a href="'.url(config('laraadmin.adminRoute') . '/poems/'.$data->data[$i][0]).'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" target="_blank"><i class="fa fa-eye"></i></a>';
                $output .= ' <a href="'.url(config('laraadmin.adminRoute') . '/poems/'.$data->data[$i][0]).'/edit'.'" class="btn btn-info btn-xs" style="display:inline;padding:2px 5px 3px 5px;" target="_blank"><i class="fa fa-pencil"></i></a>';
                $data->data[$i][] = (string)$output;
            }
        }

        $out->setData($data);
        return $out;
    }

}
