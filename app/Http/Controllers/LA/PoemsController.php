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
        $poem = DB::table('dev_poem')->where('id',$id)->first();
        $poem_content = json_decode($poem->content);
        $poem_detail = null;
        $detail = DB::table('dev_poem_detail')->where('poem_id',$id)->first();
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
        $poem = DB::table('dev_poem')->where('id',$id)->first();
        $poem_content = json_decode($poem->content);
        $poem_detail = null;
        $detail = DB::table('dev_poem_detail')->where('poem_id',$id)->first();
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
            $poem = array();
            $poem['title'] = $request->input('title');
            $poem['dynasty'] = $request->input('dynasty');
            $poem['author'] = $request->input('author');
            $poem['tags'] = $request->input('tags');
            $poem['type'] =  $request->input('type');
            $poem['background'] = $request->input('background');
            $poem['content'] = json_encode($request->input('con'));
            $poem['updated_at'] =date('Y-m-d H:i:s',time());
            $res = DB::table('dev_poem')->where('id',$id)->update($poem);
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
            $yi = $request->input('yi');
            $zhu = $request->input('zhu');
            $shang = $request->input('shang');
            $res = DB::table('dev_poem_detail')
                ->where('id',$id)
                ->update([
                    'updated_at' =>date('Y-m-d H:i:s',time()),
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
        $values = DB::table('dev_poem')
            ->select('dev_poem.id','dev_poem.title','dev_poem.dynasty','dev_poem.author','dev_poem.type','dev_poem.like_count','dev_poem.tags');
        $out = Datatables::of($values)->make();
        $data = $out->getData();

        for($i=0; $i < count($data->data); $i++) {
//            $data->data[$i][6] = json_decode($data->data[$i][6] );
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
    public function dynasty(){
        $module = array();
        $custom_menu = array();
        $custom_menu['name'] = 'poems';
        $custom_menu['id'] = '9';

        return View('la.poem.dynasty', [
            'show_actions' =>$this->show_action,
            'listing_cols' => ['id','name','alia_name','count'],
            'module' => $module,
            'custom_menu'=>$custom_menu
        ]);
    }
    /**
     * 朝代
     */
    public function dy_ajax(){
        $values = DB::table('poem_dynasty')
            ->select('poem_dynasty.id','poem_dynasty.name','poem_dynasty.alia_name','poem_dynasty.count');
        $out = Datatables::of($values)->make();
        $data = $out->getData();

        for($i=0; $i < count($data->data); $i++) {
            if($this->show_action) {
                $output = '';
                $output .= ' <a data-id="'.$data->data[$i][0].'" data-name="'.$data->data[$i][1].'"  data-alia-name="'.$data->data[$i][2].'" class="btn btn-info btn-xs" style="display:inline;padding:2px 5px 3px 5px;" target="_blank" href="#edit-modal" data-toggle="modal" data-target="#edit-modal"><i class="fa fa-pencil"></i></a>';
                $data->data[$i][] = (string)$output;
            }
        }

        $out->setData($data);
        return $out;
    }
    /**
     * 类型
     */
    public function type(){
        $module = array();
        $custom_menu = array();
        $custom_menu['name'] = 'poems';
        $custom_menu['id'] = '9';

        return View('la.poem.type', [
            'show_actions' =>$this->show_action,
            'listing_cols' => ['id','name','alia_name','count'],
            'module' => $module,
            'custom_menu'=>$custom_menu
        ]);
    }
    public function type_ajax(){
        $values = DB::table('poem_type')
            ->select('id','name','alia_name','count');
        $out = Datatables::of($values)->make();
        $data = $out->getData();

        for($i=0; $i < count($data->data); $i++) {
            if($this->show_action) {
                $output = '';
                $output .= ' <a data-id="'.$data->data[$i][0].'" data-name="'.$data->data[$i][1].'"  data-alia-name="'.$data->data[$i][2].'" class="btn btn-info btn-xs" style="display:inline;padding:2px 5px 3px 5px;" target="_blank" href="#edit-modal" data-toggle="modal" data-target="#edit-modal"><i class="fa fa-pencil"></i></a>';
                $data->data[$i][] = (string)$output;
            }
        }

        $out->setData($data);
        return $out;
    }
    /**
     * 朝代创建/修改
     * @param $request
     * @return mixed
     */
    public function dynastyStore(Request $request){
        $type = $request->input('type');
        $data = $request->input('data');
        $res = null;
        if($type =='create'){
            $res = DB::table('poem_dynasty')->insertGetId($data);
        }elseif($type =='edit'){
            $res = DB::table('poem_dynasty')->where('id',$data['id'])->update($data);
        }
        if($res){
            $_redirect_url = '/admin/poem/dynasty';
            return  redirect($_redirect_url);
        }else{
            return back()->with('error','创建失败！' );
        }
    }
    /**
     * 类型创建/修改
     * * @param $request
     * @return mixed
     */
    public function typeStore(Request $request){
        $type = $request->input('type');
        $data = $request->input('data');
        $res = null;
        if($type =='create'){
            $data['count'] = DB::table('poem')->where('type',trim($data['alia_name']))->count();
            $res = DB::table('poem_type')->insertGetId($data);
        }elseif($type =='edit'){
            $id = $request->input('type_id');
            $res = DB::table('poem_type')->where('id',$id)->update($data);
        }
        if($res){
            $_redirect_url = '/admin/poem/type';
            return  redirect($_redirect_url);
        }else{
            return back()->with('error','创建失败！' );
        }
    }
}
