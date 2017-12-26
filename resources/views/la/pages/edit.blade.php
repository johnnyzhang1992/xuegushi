@extends("la.layouts.app")

@section("contentheader_title", "专题")
@section("contentheader_description",'')
@section("section", "Page")
@section("sub_section", '编辑专题页面')
@section("htmlheader_title", '')

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
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <!-- Nav tabs -->
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="left col-md-8">
                    <h4>创建专题页面</h4>
                    <form action="/admin/pages" method="post">
                        <input type="hidden" name="type" value="edit">
                        <input type="hidden" name="page_id" value="{{@$page->id}}">
                        <section class="col-md-12">
                            <div class="input-group col-md-8">
                                <span class="input-group-addon">URL</span>
                                <input type="text" name="page[name]" class="form-control" value="{{ @$page->name}}" placeholder="name（32字符以内）">
                            </div>
                            <div class="input-group col-md-8">
                                <span class="input-group-addon">标题</span>
                                <input type="text" name="page[display_name]" class="form-control" value="{{ @$page->display_name }}" placeholder="输入标题（限64字）">
                            </div>
                            <div class="input-group col-md-8">
                                <span class="input-group-addon">标签</span>
                                <input type="text" name="page[tags]" value="{{ @$page->tags }}" class="form-control" placeholder="输入标签,英文逗号分开(可选)">
                            </div>
                            <div class="clearfix">
                                <textarea name="page[html_content]" id="summernote" cols="30" rows="10">正文内容{!! @$page->html_content!!}</textarea>
                            </div>
                        </section>
                        <div class="row col-md-12">
                            <button type="submit"  id="save-poem" class="btn btn-success">提交</button>
                        </div>
                    </form>
                </div>
                <div class="right col-md-4">
                    <h4>工具栏</h4>
                    @include('frontend.partials.side_tool')
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <link href="{{ asset('lib/summernote/dist/summernote.css') }}" rel="stylesheet" type="text/css">
    <style>
        section{
            overflow: hidden;
            clear: both;
            min-height: 40px;
            margin-bottom: 15px;
        }
        .input-group{
            margin-bottom: 10px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('lib/summernote/dist/summernote.min.js') }}"></script>
    <script src="{{ asset('lib/summernote/lang/summernote-zh-CN.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                lang: 'zh-CN', // default: 'en-US'
                height: 300,                 // set editor height
                minHeight: null,             // set minimum height of editor
                maxHeight: null,             // set maximum height of editor
                focus: true,                  // set focus to editable area after initializing summe
                callbacks: {
                    onImageUpload: function(files) {
                        //上传图片
                        var img_id = $(this);
                        var formData = new FormData();
                        formData.append("file", files[0]);
                        formData.append("_type", 'edit');
                        formData.append("page_id", '{{@$page->id}}');
                        $.ajax({
                            url: "/admin/uploads_image/page",
                            data: formData,
                            type: 'POST',
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function (data) {
                                console.log(data);
                                $(img_id).summernote('insertImage',data.url);
                            },
                            error: function () {
                                $(img_id).summernote('insertText', '上传图片失败,原因：'+data.message);
                            }
                        })
                    }
                }
            });
        });
    </script>
@endpush