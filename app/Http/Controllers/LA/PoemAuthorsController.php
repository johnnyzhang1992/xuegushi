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

class PoemAuthorsController extends Controller
{
    public $show_action = true;
    public $view_col = 'name';
    public $listing_cols = ['id','source_id','like_count','author_name','dynasty','profile','updated_at'];

    public function __construct() {
        // Field Access of Listing Columns
        if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
            $this->middleware(function ($request, $next) {
                return $next($request);
            });
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
        $custom_menu['name'] = 'authors';
        $custom_menu['id'] = '10';

        return View('la.authors.index', [
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
        $custom_menu['name'] = 'authors';
        $custom_menu['id'] = '10';
        $author = DB::table('dev_author')->where('id',$id)->first();
        $more_infos= json_decode($author->more_infos);
        return View('la.authors.show')
            ->with('author',$author)
            ->with('more_info',$more_infos)
            ->with('custom_menu',$custom_menu);
    }

    /**
     * 编辑
     * @param $id
     * @return mixed
     */
    public function edit($id){
        $custom_menu = array();
        $custom_menu['name'] = 'authors';
        $custom_menu['id'] = '10';
        $author = DB::table('dev_author')->where('id',$id)->first();
        return View('la.authors.edit')
            ->with('author',$author)
            ->with('custom_menu',$custom_menu);
    }
    function dd($id){
        $custom_menu = array();
        $custom_menu['name'] = 'authors';
        $custom_menu['id'] = '10';
        $author = DB::table('dev_author')->where('id',$id)->first();
        print_r($author);
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
            $author = array();
            $author['dynasty'] = $request->input('dynasty');
            $author['author_name'] = $request->input('author_name');
            $author['profile'] = $request->input('profile');
            $author['updated_at'] =date('Y-m-d H:i:s',time());
            $res = DB::table('dev_author')->where('id',$id)->update($author);
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
            $res = DB::table('dev_author')
                ->where('id',$id)
                ->update([
                    'updated_at' =>date('Y-m-d H:i:s',time()),
                    'more_infos' => json_encode($request->input('more_infos'))
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
     * Datatable Ajax fetch
     *
     * @return mixed
     */
    public function dtajax()
    {
        $values = DB::table('dev_author')
            ->select('id','source_id','like_count','author_name','dynasty','profile','updated_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();

        for($i=0; $i < count($data->data); $i++) {
//            $data->data[$i][3] = substr($data->data[$i][3],0,50);
            if($this->show_action) {
                $output = '';
                $output .= '<a href="'.url(config('laraadmin.adminRoute') . '/authors/'.$data->data[$i][0]).'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" target="_blank"><i class="fa fa-eye"></i></a>';
                $output .= ' <a href="'.url(config('laraadmin.adminRoute') . '/authors/'.$data->data[$i][0]).'/edit'.'" class="btn btn-info btn-xs" style="display:inline;padding:2px 5px 3px 5px;" target="_blank"><i class="fa fa-pencil"></i></a>';
                $data->data[$i][] = (string)$output;
            }
        }

        $out->setData($data);
        return $out;
    }

}
