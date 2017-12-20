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
            <div class="content">
                <h3>{{@$page->display_name}}</h3>
                <div class="tags">
                    {{ @$page->tags }}
                </div>
                <div class="page-content">
                    {!! @$page->html_content !!}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')

@endpush

@push('scripts')

@endpush