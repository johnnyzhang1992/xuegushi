@extends('zhuan.layout.zhuanlan')

@section('content-css')
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
        .note-editor.panel{
            border: none;
        }
        .panel-default>.panel-heading{
            background-color: #fff;
        }
        .WriteCover-wrapper {
            background: #f7f8f9;
            line-height: 192px;
            color: gray;
            min-height: 192px;
            text-align: center;
        }
        .WriteCover-previewWrapper {
            height: 100%;
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            position: relative;
        }
        .Layout-titleImage--normal .WriteCover-previewWrapper .TitleImage {
            margin-bottom: 0;
        }
        .Layout-titleImage--normal .TitleImage {
            margin-bottom: 24px;
        }
        .TitleImage, .TitleImage img {
            display: block;
        }
        .TitleImage, .TitleImage img {
            display: block;
        }
        .TitleImage-imagePure {
            width: 100%;
        }
        .WriteCover-editWrapper {
            position: absolute;
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
            height: 42px;
            right: 0;
            bottom: 0;
            z-index: 1;
            background: rgba(0,0,0,.75);
            border-radius: 4px 0 0 0;
            border: 0;
        }
        .titleInput,.topicInput,.RichText{
            margin: 15px 0;
        }
    </style>
@endsection

@section('content')
    <main class="main_content col-md-12 no-padding">
        <div class="content col-md-10 col-xs-12 zhuanlan-new">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="container-stream col-md-12 col-xs-12">
                <div class="left col-md-9">
                    <div class="WriteCover-wrapper">
                        <div class="WriteCover-previewWrapper WriteCover-previewWrapper--empty">
                            {{--<div class="TitleImage">--}}
                                {{--<img alt="" src="https://pic2.zhimg.com/v2-ee4ee92c39dd3bcbb2f4b21b2f2536d2_r.jpg" class="TitleImage-imagePure TitleImage-imagePure--fixed" height="240px">--}}
                            {{--</div>--}}
                            {{--<div class="WriteCover-editWrapper">--}}
                                {{--<button class="Button WriteCover-editButton WriteCover-uploadButton" title="更换" aria-label="更换" type="button">--}}
                                    {{--<i class="icon-ic_phot_camera_alt"></i>--}}
                                {{--</button>--}}
                                {{--<button class="Button WriteCover-editButton WriteCover-deleteButton" title="删除" aria-label="删除" type="button"><i class="icon-ic_phot_delete"></i></button>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                    <form action="/admin/pages" method="post">
                        <input type="hidden" name="type" value="create">
                        <div class="titleInput input-group col-md-12">
                            <input type="text" name="page[display_name]" class="form-control" value="{{ @old('page[title]') }}" placeholder="输入标题（限64字）">
                        </div>
                        <div class="topicInput input-group col-md-12">
                            <input type="text" name="page[tags]" value="{{ @old('page[tage]') }}" class="form-control" placeholder="输入标签,英文逗号分开(可选)">
                        </div>
                        <div class="RichText clearfix col-md-12 no-padding">
                            <textarea name="page[html_content]" id="summernote" cols="30" rows="10">正文内容{!! @old('page[html_content]') !!}</textarea>
                        </div>
                        <div class="row col-md-12">
                            <button type="submit"  id="save-poem" class="btn btn-success">提交</button>
                        </div>
                    </form>
                </div>
                <div class="right col-md-3">
                    @include('zhuan.partials.side_tool')
                </div>
            </div>
        </div>
    </main>
@endsection

@section('content-js')
    <script src="{{ asset('la-assets/plugins/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('lib/summernote/dist/summernote.min.js') }}"></script>
    <script src="{{ asset('lib/summernote/lang/summernote-zh-CN.js') }}"></script>
    <script src="{{ asset('lib/jquery-sticky/jquery.sticky.js') }}"></script>
    <script type="text/javascript">
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
                    },
                    onInit: function() {
                        $(".note-toolbar").sticky({
                            topSpacing:65,
                            zIndex:999
                        });
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

        $("#navigation").sticky({
            topSpacing:0,
            zIndex:999
        });
        $(".side-tool").sticky({
            topSpacing:65,
            zIndex:999
        });
        // upload
        {{--var bsurl = '{{ url('') }}';--}}
        {{--var fm_dropzone_main = null;--}}
        {{--var cntFiles = null;--}}
        {{--$(function () {--}}
            {{--fm_dropzone_main = new Dropzone("#fm_dropzone_main", {--}}
                {{--maxFilesize: 2,--}}
                {{--acceptedFiles: "image/*",--}}
                {{--init: function() {--}}
                    {{--this.on("complete", function(file) {--}}
                        {{--this.removeFile(file);--}}
                    {{--});--}}
                    {{--this.on("success", function(file) {--}}
                        {{--console.log("addedfile");--}}
                        {{--var res = JSON.parse(file.xhr.response);--}}
                        {{--console.log(JSON.parse(file.xhr.response));--}}
                        {{--$('.zhuanlan-logo svg').hide();--}}
                        {{--$('.zhuanlan-logo-img').attr('src',res.url);--}}
                    {{--});--}}
                {{--}--}}
            {{--});--}}
        {{--});--}}
    </script>
@endsection