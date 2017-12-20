@extends("la.layouts.app")

@section("contentheader_title", "专题页面")
@section("contentheader_description", "专题页面列表")
@section("section", "页面")
@section("sub_section", "列表")
@section("htmlheader_title", "专题页面列表")

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

    <div class="box box-success">
        <!--<div class="box-header"></div>-->
        <div class="box-body">
            <div class="row col-md-12">
                <a href="{{url('admin/pages/create')}}" class="btn btn-success pull-right"><i class="fa fa-pencil"></i> 创建</a>
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
                ajax: "{{ url(config('laraadmin.adminRoute') . '/pages_dt_ajax') }}",
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