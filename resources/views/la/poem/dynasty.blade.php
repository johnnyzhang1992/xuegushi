@extends("la.layouts.app")

@section("contentheader_title", "朝代")
@section("contentheader_description", "朝代 listing")
@section("section", "朝代")
@section("sub_section", "Listing")
@section("htmlheader_title", "朝代 Listing")

@section("headerElems")

@endsection

@section("main-content")

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-dismissable alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <ul>
                {{ session()->get('error') }}
            </ul>
        </div>
    @endif
    <div class="box box-success">
        <!--<div class="box-header"></div>-->
        <div class="box-body">
            <div class="header">
                <h3>
                    朝代列表
                    <a id="create_btn" class="btn btn-success" href="#create-modal" data-toggle="modal" data-target="#create-modal">
                        <i class="fa fa-pencil"></i>  添加
                    </a>
                </h3>
            </div>
            <table id="example1" class="table table-bordered">
                <thead>
                <tr class="success">
                    @foreach( $listing_cols as $col )
                        <th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>
                    @endforeach
                    @if($show_actions)
                        <th>Actions</th>
                    @endif
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">创建朝代名称</h4>
                </div>
                {{--<div class="modal-body">--}}
                {{--请填写以下信息<span id="span-title"></span>--}}
                {{--</div>--}}
                <div class="modal-footer">
                    <form action="{{ url('/admin/poem/dynasty/save') }}" id="delete-form" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="type" value="create">
                        <div class="form-group clearfix">
                            <label class="col-sm-2 control-label" for="name">name: <span style="color:red">*</span></label>
                            <div class="col-sm-10">
                                <input id="name"  type="text"  name="data[name]" placeholder="填写英文字段,不可重复"  class="form-control">
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="col-sm-2 control-label" for="alia_name">拓展名: <span style="color:red">*</span></label>
                            <div class="col-sm-10">
                                <input id="alia_name" type="text"  name="data[alia_name]" placeholder="填写中文名"  class="form-control">
                            </div>
                        </div>
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button id="create-btn" type="submit" class="btn btn-primary">确定</button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
    <script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
    <script>
        $(function () {
            $("#example1").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url(config('laraadmin.adminRoute') . '/poem_dy_ajax') }}",
                language: {
                    lengthMenu: "_MENU_",
                    search: "_INPUT_",
                    searchPlaceholder: "Search"
                },
                @if($show_actions)
                columnDefs: [ { orderable: false, targets: [-1] }],
                @endif
            });
            $("#user-add-form").validate({

            });
        });
    </script>
@endpush