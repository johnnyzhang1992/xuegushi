@extends('zhuan.layout.zhuanlan')

@section('content-css')
    <link href="{{ asset('lib/summernote/dist/summernote.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('lib/chosen/chosen.min.css') }}" rel="stylesheet" type="text/css">
    <style>
        .container-stream {
            padding-bottom: 30px;
        }
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
            content: "\6dfb\52a0\5c01\9762\56fe";
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
        .post-buttons{
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <main class="main_content col-md-12 no-padding">
        <div class="content col-md-10 col-xs-12 zhuanlan-new">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="container-stream col-md-12 col-xs-12">
                <div class="left col-md-9">
                    <ol class="breadcrumb">
                        <li>写文章</li>
                        <li class="active">草稿自动保存</li>
                    </ol>
                    <div class="WriteCover-wrapper clearfix">
                        <input type="hidden" name="post[id]" value="{{@$post->id}}">
                        <input type="hidden" name="post[status]" value="{{@$post->status}}">
                        <div class="WriteCover-previewWrapper WriteCover-previewWrapper--empty">
                            <form action="{{ url('uploads_image/post_cover') }}" id="fm_dropzone_main" enctype="multipart/form-data" method="POST">
                                {{ csrf_field() }}
                                <div class="cover-uploadIcon dz-message" style="display: none">
                                    <i class="fa fa-camera"></i>
                                </div>
                            </form>
                            <div class="TitleImage">
                                <img alt="" src="{{ @$post->cover_url }}" id="postCoverImage" class="TitleImage-imagePure TitleImage-imagePure--fixed" height="240px">
                            </div>
                            <div class="WriteCover-editWrapper clearfix">
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
                        <input type="text" name="post[title]" class="form-control" value="{{ @$post->title}}" placeholder="输入标题（限64字）">
                    </div>
                    <div class="topicInput input-group col-md-12">
                        <input type="text" name="post[tags]" value="{{ @$post->topic }}" class="form-control" placeholder="输入标签,英文逗号分开(可选)">
                    </div>
                    @if(isset($zhuans) && $zhuans)
                        <div class="col-md-12 no-padding">
                            <div class="col-md-2 no-padding"><label for="zhuanlan">选择所属专栏(可多选)</label></div>
                            <div class="col-md-10 no-padding">
                                <select id="zhuanlan" class="form-control chosen"  data-placeholder="选择文章归属专栏">
                                    @foreach($zhuans as $zhuan)
                                        <option value="{{@$zhuan->id}}">{{@$zhuan->alia_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="RichText clearfix col-md-12 no-padding">
                        <textarea name="post[html_content]" id="summernote" cols="30" rows="10">{!! @$post->content !!}</textarea>
                    </div>
                    <div class="post-buttons row col-md-12">
                        <a href="{{url('/post/'.@$post->id.'/preview')}}"  target="_blank"  id="preview-post" class="btn btn-success">预览</a>
                        <button type="button"  id="draft-post" class="btn btn-success">保存草稿</button>
                        <button type="button"  id="save-post" class="btn btn-success">立即发布</button>
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
    <script src="{{ asset('lib/chosen/chosen.jquery.min.js') }}"></script>
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
                        formData.append("_token",$('input[name="_token"]').val() );
                        formData.append("type_id",$('input[name="post[id]"]').val() );
                        $.ajax({
                            url: "/uploads_image/post",
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
        $('.chosen').chosen({});
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
        });
        function saveDraft() {
            // 只要一项不为空即自动保存更新（除标签外
            var id = $('input[name="post[id]"]').val();
            var status = $('input[name="post[status]"]').val();
            var title = $('input[name="post[title]"]').val();
            var tags = $('input[name="post[tags]"]').val();
            var content = $('textarea[name="post[html_content]"]').val();
            var cover_image = $('#postCoverImage').attr('src');
            var zhuanlans = -1;
            if ( $('#zhuanlan').length > 0 ) {
                zhuanlans = $('#zhuanlan').val()
            }
            var data = {
                'id': id,
                'title':title,
                'topic':tags,
                'content':content,
                'status':status,
                'cover_image':cover_image,
                'zhuanlan':zhuanlans,
                '_token':$('input[name="_token"]').val()
            };
            if(title !='' || content !='' || cover_image !=''){
                console.log('------draft---');
                // console.log(data);
                postData(data);
            }
        }
        var _interval = 60;//保存时间间隔(秒)
        setInterval("saveDraft()",1000*_interval);
        function postData(data) {
            $.ajax({
                url: '/post/update',
                data:data,
                type: 'POST',
                cache: false,
                success: function (res) {
                    console.log(res);
                    if(res.status == 'success'){
                        $('body').toast({
                            position:'fixed',
                            content:'自动保存成功！',
                            duration:1000,
                            isCenter:true,
                            background:'rgba(0,0,0,0.5)',
                            animateIn:'bounceIn-hastrans',
                            animateOut:'bounceOut-hastrans'
                        });
                    }
                },
                error: function (res) {
                    console.log(res);
                }
            });

        }
        $('#save-post').on('click',function () {
            // 修改状态为active 并且跳转到详情页
            var id = $('input[name="post[id]"]').val();
            var title = $('input[name="post[title]"]').val();
            var tags = $('input[name="post[tags]"]').val();
            var content = $('textarea[name="post[html_content]"]').val();
            var cover_image = $('#postCoverImage').attr('src');
            var zhuanlans = -1;
            if ( $('#zhuanlan').length > 0 ) {
                zhuanlans = $('#zhuanlan').val()
            }
            var data = {
                'id': id,
                'title':title,
                'topic':tags,
                'content':content,
                'status':'active',
                'cover_image':cover_image,
                'zhuanlan':zhuanlans,
                '_token':$('input[name="_token"]').val()
            };
            if(title !='' || content !='' || cover_image !=''){
                console.log('------draft---');
                // console.log(data);
                $.ajax({
                    url: '/post/update',
                    data:data,
                    type: 'POST',
                    cache: false,
                    success: function (res) {
                        console.log(res);
                        if(res.status == 'success'){
                            $('body').toast({
                                position:'fixed',
                                content:'发布成功',
                                duration:1000,
                                isCenter:true,
                                background:'rgba(0,0,0,0.5)',
                                animateIn:'bounceIn-hastrans',
                                animateOut:'bounceOut-hastrans'
                            });
                            setTimeout(function () {
                                window.location.href = 'https://zhuanlan.xuegushi.cn/post/'+id;
                            },2000)
                        }
                    },
                    error: function (res) {
                        console.log(res);
                    }
                });
            }
        });
        $('#draft-post').on('click',function () {
            // 修改状态为active 并且跳转到详情页
            var id = $('input[name="post[id]"]').val();
            var title = $('input[name="post[title]"]').val();
            var tags = $('input[name="post[tags]"]').val();
            var content = $('textarea[name="post[html_content]"]').val();
            var cover_image = $('#postCoverImage').attr('src');
            var zhuanlans = -1;
            if ( $('#zhuanlan').length > 0 ) {
                zhuanlans = $('#zhuanlan').val()
            }
            var data = {
                'id': id,
                'title':title,
                'topic':tags,
                'content':content,
                'status':'draft',
                'cover_image':cover_image,
                'zhuanlan':zhuanlans,
                '_token':$('input[name="_token"]').val()
            };
            if(title !='' || content !='' || cover_image !=''){
                console.log('------draft---');
                // console.log(data);
                $.ajax({
                    url: '/post/update',
                    data:data,
                    type: 'POST',
                    cache: false,
                    success: function (res) {
                        console.log(res);
                        if(res.status == 'success'){
                            $('body').toast({
                                position:'fixed',
                                content:'草稿保存成功',
                                duration:1000,
                                isCenter:true,
                                background:'rgba(0,0,0,0.5)',
                                animateIn:'bounceIn-hastrans',
                                animateOut:'bounceOut-hastrans'
                            });
                            setTimeout(function () {
                                window.location.href = 'https://zhuanlan.xuegushi.cn/post/'+id+'/preview';
                            },2000)
                        }
                    },
                    error: function (res) {
                        console.log(res);
                    }
                });
            }
        })
    </script>
@endsection