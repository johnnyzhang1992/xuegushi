@extends("la.layouts.app")

@section("contentheader_title", "专题")
@section("contentheader_description",'')
@section("section", "Poem")
@section("sub_section", '创建专题页面')
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
                        <input type="hidden" name="type" value="create">
                        <section class="col-md-12">
                            <div class="input-group col-md-8">
                                <span class="input-group-addon">URL</span>
                                <input type="text" name="page[name]" class="form-control" value="{{ @old('page[name]') }}" placeholder="name（32字符以内）">
                            </div>
                            <div class="input-group col-md-8">
                                <span class="input-group-addon">标题</span>
                                <input type="text" name="page[display_name]" class="form-control" value="{{ @old('page[title]') }}" placeholder="输入标题（限64字）">
                            </div>
                            <div class="input-group col-md-8">
                                <span class="input-group-addon">标签</span>
                                <input type="text" name="page[tags]" value="{{ @old('page[tage]') }}" class="form-control" placeholder="输入标签,英文逗号分开(可选)">
                            </div>
                            <div class="clearfix">
                                <textarea name="page[html_content]" id="summernote" cols="30" rows="10">正文内容{!! @old('page[html_content]') !!}</textarea>
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
        .tool-content{
            position: relative;
        }
        .tool-content ul.list-items{
            position: absolute;
            top: 34px;
            right: 0;
            width: 100%;
            padding-left: 0;
            padding-right: 0;
            padding-top: 10px;
            padding-bottom: 10px;
            list-style: none;
            z-index: 999;
            opacity: 0.9;
            background-color: #e6e6e6;
        }
        .tool-content .list-items .item a{
            display: block;
            width: 100%;
            color: #333;
            line-height: 25px;
            padding-left: 15px;
            padding-right: 15px;
        }
        .tool-content .list-items .item a:hover{
            background-color: #0d6aad;
            color: #fff;
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
                        formData.append("_type", 'create');
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
            function getSearchPoem() {
                $.get(
                    '/search/poem',
                    {
                        'value': $('#search-poem').val(),
                        '_token': $('input[name="_token"]').val()
                    },
                    function (res) {
                        var html = '';
                        if(res.length>0){
                            for(var i =0;i<res.length;i++){
                                html = html + '<li class="item"><a class="item-link" data-href="https://xuegushi.cn/poem/'+res[i].id+'" data-title="'+res[i].title+'" data-dynasty="'+res[i].dynasty+'" data-author="'+res[i].author+'">《'+ res[i].title+'》 -'+res[i].dynasty+':'+res[i].author+'</a></li>'
                            }
                            $('.poem-lists').html(html).show();
                        }
                        // console.log(res);
                    }
                )
            }
            function getSearchAuthor() {
                $.get(
                    '/search/author',
                    {
                        'value': $('#search-author').val(),
                        '_token': $('input[name="_token"]').val()
                    },
                    function (res) {
                        var html = '';
                        if(res.length>0){
                            for(var i =0;i<res.length;i++){
                                html = html + '<li class="item"><a class="item-link" data-href="https://xuegushi.cn/author/'+res[i].id+'" data-dynasty="'+res[i].dynasty+'" data-author="'+res[i].author_name+'">'+res[i].dynasty+':'+res[i].author_name+'</a></li>'
                            }
                            $('.author-lists').html(html).show();
                        }
                        // console.log(res);
                    }
                )
            }
            $('#search-poem').bind('input propertychange', function() {
                if($('#search-poem').val() !=''){
                    getSearchPoem();
                }
            });
            $('#search-author').bind('input propertychange', function() {
                if($('#search-author').val() !=''){
                    getSearchAuthor();
                }
            });
            $('.poem-lists').on('click','a',function(){
                $('#tool-poem-url').val($(this).attr('data-href'));
                $('#tool-poem-title').val($(this).attr('data-title'));
                $('#tool-poem-dynasty').val($(this).attr('data-dynasty'));
                $('#tool-poem-author').val($(this).attr('data-author'));
                $('.poem-lists').hide();
            });
            $('.author-lists').on('click','a',function(){
                $('#tool-author-url').val($(this).attr('data-href'));
                $('#tool-author-dynasty').val($(this).attr('data-dynasty'));
                $('#tool-author-author').val($(this).attr('data-author'));
                $('.author-lists').hide();
            })
        });
    </script>
@endpush