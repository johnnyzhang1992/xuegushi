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
use Storage;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;

class PagesController extends Controller
{
    public $show_action = true;
    public $view_col = 'name';
    public $listing_cols = ['id','creator_id','name','display_name','tags','pv_count','like_count','collect_count'];

    public function __construct() {
        // Field Access of Listing Columns
        if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
            $this->middleware(function ($request, $next) {
                $this->listing_cols = ['id','creator_id','name','display_name','tags','pv_count','like_count','collect_count'];
                return $next($request);
            });
        } else {
            $this->listing_cols = ['id','creator_id','name','display_name','tags','pv_count','like_count','collect_count'];
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
        $custom_menu['name'] = 'pages';
        $custom_menu['id'] = '11';
        return View('la.pages.index', [
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
        $custom_menu['name'] = 'pages';
        $custom_menu['id'] = '11';
        $pages = DB::table('dev_pages')->where('id',$id)->first();
        return View('la.pages.show')
            ->with('page',$pages)
            ->with('custom_menu',$custom_menu);
    }

    public function create(){
        return view('la.pages.create');
    }
    /**
     * 编辑
     * @param $id
     * @return mixed
     */
    public function edit($id){
        $custom_menu = array();
        $custom_menu['name'] = 'pages';
        $custom_menu['id'] = '11';
        $pages = DB::table('dev_pages')->where('id',$id)->first();
        return View('la.pages.edit')
            ->with('page',$pages)
            ->with('custom_menu',$custom_menu);
    }
    /**
     * 保存
     * @param $request
     * @return mixed
     */
    public function store(Request $request){
        $type = $request->input('type');
        $page = $request->input('page');
        $page['creator_id'] = Auth::user()->id;
        $page['updated_at'] = date('Y-m-d H:i:s',time());
        if($type =='create'){
            $page['created_at'] = date('Y-m-d H:i:s',time());
            $page_id = DB::table('dev_pages')->insertGetId($page);
            DB::table('dev_pages')->where('id',$page_id)->update([
                'html_content' =>str_replace('static/page/tmps','static/page/'.$page_id,$page['html_content'])
            ]);
            if(is_dir(public_path('static/page/'.$page_id))) {
                // ok,
            } else {
                if(mkdir(public_path('static/page/'.$page_id), 0777, true)) {
                    // ok,
                }
            }
            $photos = DB::table('dev_photo')->where('type','page')->whereNull('type_id')->get();
            foreach ($photos as $photo){
                if(file_exists('static/page/tmps/'.$photo->name)){
                    DB::table('dev_photo')->where('id',$photo->id)->update(['type_id'=>$page_id]);
                    Storage::move('static/page/tmps/'.$photo->name,'static/page/'.$page_id.'/'.$photo->name);
                }
            }
        }else{
            $page_id = $request->input('page_id');

            DB::table('dev_pages')->where('id',$page_id)->update([
                'name'=>$page['name'],
                'display_name'=>$page['display_name'],
                'tags'=>$page['tags'],
                'html_content'=>$page['html_content'],
                'editor_id'=>Auth::user()->id,
                'updated_at'=>date('Y-m-d H:i:s',time())
            ]);
        }
        return redirect('admin/pages');
    }
    /**
     * Datatable Ajax fetch
     *
     * @return mixed
     */
    public function dtajax()
    {
        $values = DB::table('dev_pages')
            ->select('dev_pages.id','dev_pages.creator_id','dev_pages.name','dev_pages.display_name','dev_pages.tags','dev_pages.pv_count','dev_pages.like_count','dev_pages.collect_count');
        $out = Datatables::of($values)->make();
        $data = $out->getData();

        for($i=0; $i < count($data->data); $i++) {
//            $data->data[$i][6] = json_decode($data->data[$i][6] );
            if($this->show_action) {
                $output = '';
                $output .= '<a href="'.url(config('laraadmin.adminRoute') . '/pages/'.$data->data[$i][0]).'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;" target="_blank"><i class="fa fa-eye"></i></a>';
                $output .= ' <a href="'.url(config('laraadmin.adminRoute') . '/pages/'.$data->data[$i][0]).'/edit'.'" class="btn btn-info btn-xs" style="display:inline;padding:2px 5px 3px 5px;" target="_blank"><i class="fa fa-pencil"></i></a>';
                $data->data[$i][] = (string)$output;
            }
        }

        $out->setData($data);
        return $out;
    }
}
