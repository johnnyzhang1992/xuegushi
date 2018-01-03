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
        .titleInput,.topicInput,.RichText{
            margin: 15px 0;
        }
        .cover-uploadIcon{
            font-size: 36px;
        }
        .cover-uploadIcon::after{
            content: "\6DFB\52A0\9898\56FE";
            color: #b3b3b3;
            position: absolute;
            width: 100%;
            text-align: center;
            left: 0;
            bottom: 52px;
            line-height: 1;
            opacity: 0;
            z-index: 0;
            -webkit-transform: translateY(-12px);
            transform: translateY(-12px);
            -webkit-transition: all .2s;
            transition: all .2s;
            font-size: 20px;
        }
        .cover-uploadIcon:hover{
            cursor: pointer;
        }
        .cover-uploadIcon:hover::after{
            opacity: 1;
            -webkit-transform: translateY(0);
            transform: translateY(0);
        }
        .WriteCover-editWrapper{
            position: absolute;
            height: 48px;
            width: 98px;
            bottom: 0;
            right: 0;
            line-height: 48px;
            background: rgba(0,0,0,.75);
            border-radius: 4px 0 0 0;
            border: 0;
        }
        .WriteCover-editWrapper .Button{
            width: 48px;
            height: 48px;
            float: left;
            border: 0;
            border-radius: 0;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            padding: 0;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        .Button{
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            background: inherit;
        }
        .Button:hover {
            background-color: rgba(207,216,230,.1);
        }
        .dz-preview{
            display: none;
        }
    </style>
@endsection

@section('content')
    <main class="main_content col-md-12 no-padding">
        <div class="content col-md-10 col-xs-12 zhuanlan-new">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="container-stream col-md-12 col-xs-12">
                <div class="left col-md-9">
                    <div class="WriteCover-wrapper clearfix">
                        <div class="WriteCover-previewWrapper WriteCover-previewWrapper--empty">
                            <form action="{{ url('uploads_image/post_cover') }}" id="fm_dropzone_main" enctype="multipart/form-data" method="POST">
                                {{ csrf_field() }}
                                <div class="cover-uploadIcon dz-message">
                                    <i class="fa fa-camera"></i>
                                </div>
                            </form>
                            <div class="TitleImage" style="display: none">
                                <img alt="" src="" class="TitleImage-imagePure TitleImage-imagePure--fixed" height="240px">
                            </div>
                            <div class="WriteCover-editWrapper clearfix" style="display: none">
                                <div class="WriteCover-previewWrapper WriteCover-previewWrapper--empty">
                                    <form action="{{ url('uploads_image/post_cover') }}" id="fm_dropzone_main1" enctype="multipart/form-data" method="POST">
                                        {{ csrf_field() }}
                                        <button class="Button editButton dz-message" title="更换" data-toggle="tooltip" data-placement="top" type="button">
                                            <i class="fa fa-camera"></i>
                                        </button>
                                    </form>
                                <button class="Button deleteButton" title="删除" data-toggle="tooltip" data-placement="top" type="button"><i class="fa fa-trash-o"></i></button>
                            </div>
                        </div>
                        </div>
                    </div>
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
                height: 'auto',                 // set editor height
                minHeight: 300,             // set minimum height of editor
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
        var bsurl = '{{ url('') }}';
        var fm_dropzone_main = null;
        var cntFiles = null;
        $(function () {
            fm_dropzone_main = new Dropzone("#fm_dropzone_main", {
                maxFilesize: 2,
                acceptedFiles: "image/*",
                init: function() {
                    this.on("complete", function(file) {
                        this.removeFile(file);
                    });
                    this.on("success", function(file) {
                        var res = JSON.parse(file.xhr.response);
                        console.log(JSON.parse(file.xhr.response));
                        $('.cover-uploadIcon').hide();
                        $('.TitleImage').show();
                        $('.WriteCover-editWrapper').show();
                        $('.TitleImage-imagePure').attr('src',res.url);
                    });
                }
            });
        });
        $(function () {
            fm_dropzone_main = new Dropzone("#fm_dropzone_main1", {
                maxFilesize: 2,
                acceptedFiles: "image/*",
                init: function() {
                    this.on("complete", function(file) {
                        this.removeFile(file);
                    });
                    this.on("success", function(file) {
                        var res = JSON.parse(file.xhr.response);
                        console.log(JSON.parse(file.xhr.response));
                        $('.cover-uploadIcon').hide();
                        $('.TitleImage').show();
                        $('.WriteCover-editWrapper').show();
                        $('.TitleImage-imagePure').attr('src',res.url);
                    });
                }
            });
        });
        $('.deleteButton').on('click',function () {
            $('.cover-uploadIcon').show();
            $('.TitleImage').hide();
            $('.WriteCover-editWrapper').hide();
            $('.TitleImage-imagePure').attr('src','');
        })
    </script>
@endsection