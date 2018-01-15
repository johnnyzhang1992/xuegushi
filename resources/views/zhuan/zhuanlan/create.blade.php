@extends('zhuan.layout.zhuanlan')

@section('content-css')
    <style>
        .apply-header .apply-title {
            font-size: 32px;
            color: #2D2D2F;
            font-weight: bold;
            line-height: 45px;
        }
        .zhuanlan-apply-body {
            margin-top: 25px;
        }
        .zhuanlan-apply-body .tip {
            font-size: 14px;
            color: #818181;
            line-height: 23px;
        }
        .zhuanlan-apply-body .item {
            width: 100%;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            margin-top: 40px;
            line-height: 36px;
        }
        .item label {
            line-height: 23px;
            font-size: 16px;
            color: #2D2D2F;
            min-width: 96px;
            text-align: left;
            margin-top: 6.5px;
            line-height: 23px;
            height: 23px;
            font-weight: initial;
        }
        .item .item-value {
            margin-left: 104px;
            width: 79%;
        }
        .item .zhuanlan-logo-dropzone {
            width: 80px;
            height: 80px;
        }
        .item .zhuanlan-logo {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            position: relative;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }
        .item .zhuanlan-logo .zhuanlan-logo-img {
            max-height: 80px;
            max-width: 80px;
            z-index: 10000;
            border-radius: 3px;
        }
        .edit-image-div {
            position: absolute;
            z-index: 20000;
            left: 40px;
            margin-left: -15px;
            height: 80px;
        }
        .edit-image-div.no-logo {
            margin-left: 0;
            left: 0;
            top: 0;
        }
       .edit-image-div {
            position: absolute;
            z-index: 20000;
            left: 40px;
            margin-left: -15px;
            height: 80px;
        }
        .item .item-value input, .item .item-value textarea {
            border: 1px solid #EDEDED;
            border-radius: 4px;
            width: 100%;
        }
        .item .item-value span.tip {
            display: block;
            font-size: 14px;
        }
        .item .item-value .with-prefix {
            width: 100%;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }
        .item .item-value .with-prefix span {
            font-size: 16px;
            color: #2D2D2F;
            line-height: 23px;
        }
        .item .item-value .zhuanlan-domain {
            margin-left: 8px;
        }
        .item.submit-item {
            margin-left: 201px;
        }
        input[type=text]:focus, textarea[type=text]:focus {
            border: 1px solid #4990E2 !important;
            border-radius: 4px !important;
            outline: none;
        }
        .item .item-value input {
            height: 36px;
            color: #2D2D2F;
            padding-left: 10px;
            font-size: 16px;
            color: #2D2D2F;
            line-height: 36px;
        }
        .item .item-value textarea {
            height: 100px;
            color: #2D2D2F;
            padding-left: 10px;
            font-size: 16px;
            color: #2D2D2F;
            line-height: 23px;
        }
        .item .item-value textarea {
            border: 1px solid #EDEDED;
            border-radius: 4px;
            width: 100%;
        }
        .item .item-value span.tip span.error {
            float: right;
            color: red;
        }
    </style>
@endsection

@section('content')
    <main class="main_content col-md-12 no-padding">
        <div class="content col-md-9 col-xs-12 zhuanlan-new">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="container-stream col-md-9 col-md-offset-1 col-xs-12">
                <div class="apply-header"><span class="apply-title">申请专栏</span></div>
                <div class="zhuanlan-apply-body">
                   <div class="tip">专栏需要有明确的写作方向，如果您在某个领域有深度的研究，欢迎开通自己的专栏分享自己的观点</div>
                    <div class="item">
                        <label>专栏主题</label>
                        <div class="item-value">
                            <input type="text" name="name" class="zhuanlan-theme" data-required-error="必须填写专栏主题" value="">
                            <span class="tip">至少填写一个主题；例如：古诗鉴赏,名人轶事<span class="zhuanlan-theme-count"></span><span class="error hidden">错误提示</span></span>
                        </div>
                    </div>
                    <div class="item zhuanlan-logo-item">
                        <label>专栏头像</label>
                        <div class="item-value">
                            <form action="{{ url('uploads_image/avatar') }}" id="fm_dropzone_main" enctype="multipart/form-data" method="POST">
                                {{ csrf_field() }}
                                <div class="dz-message">
                                    <div class="zhuanlan-logo-dropzone dz-clickable"><div class="zhuanlan-logo">
                                            <img class="zhuanlan-logo-img" src="">
                                            <div class="edit-avatar-mask hidden"></div>
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="27px" viewBox="0 0 30 27" version="1.1" class="edit-image-div has-logo">
                                                <!-- Generator: Sketch 44.1 (41455) - http://www.bohemiancoding.com/sketch -->
                                                <title>icon_photo_1</title>
                                                <desc>Created with Sketch.</desc>
                                                <defs></defs>
                                                <g id="切图" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <g transform="translate(-396.000000, -110.000000)" id="icon_photo_1">
                                                        <g transform="translate(393.000000, 107.000000)">
                                                            <polygon id="Shape" points="0 0 36 0 36 36 0 36"></polygon>
                                                            <circle id="Oval" fill="#FFFFFF" cx="18" cy="18" r="4.8"></circle>
                                                            <path d="M13.5,3 L10.755,6 L6,6 C4.35,6 3,7.35 3,9 L3,27 C3,28.65 4.35,30 6,30 L30,30 C31.65,30 33,28.65 33,27 L33,9 C33,7.35 31.65,6 30,6 L25.245,6 L22.5,3 L13.5,3 L13.5,3 Z M18,25.5 C13.86,25.5 10.5,22.14 10.5,18 C10.5,13.86 13.86,10.5 18,10.5 C22.14,10.5 25.5,13.86 25.5,18 C25.5,22.14 22.14,25.5 18,25.5 L18,25.5 Z" id="Shape" fill="#FFFFFF"></path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="80px" height="80px" viewBox="0 0 80 80" version="1.1" class="edit-image-div no-logo">
                                                <!-- Generator: Sketch 44.1 (41455) - http://www.bohemiancoding.com/sketch -->
                                                <title></title>
                                                <desc>Created with Sketch.</desc>
                                                <defs>
                                                    <rect id="path-1" x="0" y="0" width="80" height="80" rx="10"></rect>
                                                    <mask id="mask-2" maskContentUnits="userSpaceOnUse" maskUnits="objectBoundingBox" x="0" y="0" width="80" height="80" fill="white">
                                                        <use xlink:href="#path-1"></use>
                                                    </mask>
                                                </defs>
                                                <g id="切图" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <g transform="translate(-101.000000, -551.000000)" id="icon_photo_2">
                                                        <g transform="translate(101.000000, 551.000000)">
                                                            <use id="Mask" stroke="#EDEDED" mask="url(#mask-2)" stroke-width="4" fill="#FFFFFF" stroke-dasharray="6,3" xlink:href="#path-1"></use>
                                                            <g id="Group-Copy-2" opacity="0.800000012" transform="translate(22.000000, 22.000000)">
                                                                <polygon id="Shape" points="0 0 36 0 36 36 0 36"></polygon>
                                                                <circle id="Oval" fill="#EDEDED" cx="18" cy="18" r="4.8"></circle>
                                                                <path d="M13.5,3 L10.755,6 L6,6 C4.35,6 3,7.35 3,9 L3,27 C3,28.65 4.35,30 6,30 L30,30 C31.65,30 33,28.65 33,27 L33,9 C33,7.35 31.65,6 30,6 L25.245,6 L22.5,3 L13.5,3 L13.5,3 Z M18,25.5 C13.86,25.5 10.5,22.14 10.5,18 C10.5,13.86 13.86,10.5 18,10.5 C22.14,10.5 25.5,13.86 25.5,18 C25.5,22.14 22.14,25.5 18,25.5 L18,25.5 Z" id="Shape" fill="#EDEDED"></path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <span class="tip">图片尺寸最好为200*200或小于1M的正方形图片（jpg,png）</span>
                        </div>
                    </div>
                    <div class="item">
                        <label>专栏名称</label>
                        <div class="item-value">
                            <input type="text" name="name" class="zhuanlan-name  " data-required-error="必须填写专栏名称" onkeyup="widthCheck(this, 30, 20, '.zhuanlan-name-count')" value="">
                            <span class="tip">专栏名称不能超过15个汉字<span class="zhuanlan-name-count"></span><span class="error hidden">错误提示</span></span>
                        </div>
                    </div>

                    <div class="item area-item">
                        <label>专栏介绍</label>
                        <div class="item-value">
                            <textarea type="text" name="bio" class="zhuanlan-bio" id="zhuanlan-bio" rows="1" data-required="true" data-required-error="必须填写专栏介绍" data-autosize-on="true" style="overflow: hidden; word-wrap: break-word; height: 100px; min-height: 100px;"></textarea>
                            <span class="tip">关于专栏介绍会显示在您的专栏主页上，至关重要。务必简洁，少于140个汉字。<span class="zhuanlan-bio-count"></span><span class="error hidden">错误提示</span></span>
                        </div>
                    </div>

                    <div class="item area-item">
                        <label>自我介绍</label>
                        <div class="item-value">
                            <textarea type="text" name="specialty" class="zhuanlan-specialty" id="zhuanlan-specialty" rows="1" data-required-error="必须填写自我介绍" data-autosize-on="true" style="overflow: hidden; word-wrap: break-word; height: 100px; min-height: 100px;"></textarea>
                            <span class="tip">这关系到您申请的专栏是否被通过<span class="error hidden">错误提示</span></span>
                        </div>
                    </div>
                    <div class="item">
                        <label>微信号</label>
                        <div class="item-value">
                            <input type="text" name="wechat" class="zhuanlan-wechat " value="" placeholder="">
                            <span class="tip">微信号方便管理员联系您，其他用户不可见<span class="zhuanlan-name-count"></span><span class="error hidden">错误提示</span></span>
                        </div>
                    </div>
                    <div class="item">
                        <label>专栏域名</label>
                        <div class="item-value">
                            <div class="with-prefix">
                                <span class="prefix">zhuanlan.xuegushi.cn/</span>
                                <input type="text" name="domain" class="zhuanlan-domain " onkeyup="judgeDomain(this)" data-required="true" data-max-length="50" data-min-length="4" data-required-error="必须填写专栏域名" value="">
                            </div>
                            <span class="tip">域名确定后不可修改<span class="error  zhuanlan-domain-error hidden">错误提示</span></span>
                        </div>
                    </div>

                    <div class="item submit-item xzl-button-set">
                        <button type="submit" class="button submit-button btn btn-primary">
                            <span class="button-label  xzl-default-state-btn js-buttonLabel">提交申请</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('content-js')
    <script src="{{ asset('la-assets/plugins/dropzone/dropzone.js') }}"></script>
    <script type="text/javascript">
        $(".zhuanlan-new .item-value button.price").click(function(){
            $(".zhuanlan-new button.price").removeClass("selected");
            $(this).addClass("selected");

            $(".custom-price-item").addClass("hidden");
            $(".confirmed-custom-price-div").addClass("hidden");
            $(".custom-price").removeClass("hidden");
            $(".confirmed-custom-price-div").data("price", '')
        });

        $(".zhuanlan-new .item-value button.discount").click(function(){
            $(".zhuanlan-new button.discount").removeClass("selected");
            $(this).addClass("selected")
        });
        //$('.bootstrap-tagsinput').prepend($(".zhuanlan-user-names").html())

        $(".zhuanlan-new .submit-button").click(function(){
            validateAll();
            var error = null;
            for(var i=0; i< $(".error").length; i++){
                if(!$($(".error")[i]).hasClass("hidden")){
                    error = "请完善资料后在进行提交"
                }
            }
            if($(".submit-button").hasClass("disabled")){
                return
            }
            if(!!error){
                $(".submit-button").addClass("disabled");
                alert(error);
                return
            }

            var data = {
                alia_name: $(".zhuanlan-name").val(),//alia_name
                theme: $('.zhuanlan-theme').val(),//topic
                bio: $(".zhuanlan-bio").val(),//about
                specialty: $(".zhuanlan-specialty").val(),//specialty
                name: $(".zhuanlan-domain").val(),//name
                logo_url: $(".zhuanlan-logo-img").attr("src"),//avatar
                wechat: $(".zhuanlan-wechat").val(),//wechat
                _token: $('input[name="_token"]').val()
            };

            if($(".share_button").length > 0){
                data["show_share_button"] = $(".share_button .checkbox-item.checked").data("value")
            }
            // ajax 提交
            $.ajax({
                dataType: 'json',
                data:data,
                type:'POST',
                url: "/zhuanlan/create",
                success: function ( json ) {
                    // console.log(json);
                    if(json.message == 'success'){
                        $('body').toast({
                            position:'fixed',
                            content:'提交成功，请耐心等待审核',
                            duration:1000,
                            isCenter:true,
                            background:'rgba(0,0,0,0.5)',
                            animateIn:'bounceIn-hastrans',
                            animateOut:'bounceOut-hastrans'
                        });
                        setTimeout(function () {
                            window.location.href = '/'+$(".zhuanlan-domain").val();
                        },2000)
                    }else{
                        $('body').toast({
                            position:'fixed',
                            content:'提交失败，请再次提交或者刷新重试',
                            duration:1000,
                            isCenter:true,
                            background:'rgba(0,0,0,0.5)',
                            animateIn:'bounceIn-hastrans',
                            animateOut:'bounceOut-hastrans'
                        });
                    }
                }
            });
        });
        $(".zhuanlan-specialty, .zhuanlan-bio").css({"min-height": "100px"});
        $(".zhuanlan-new input, .zhuanlan-new textarea").blur(function(){
            var error = null;
            if($(this).data("min-length") && $(this).val().length < $(this).data("min-length")){
                error = $(this).parent().parent().find("label").text().replace("*", "")+ "不能少于" + $(this).data("min-length") + "个文字"
            }
            if($(this).data("max-length") && $(this).val().length > $(this).data("max-length")){
                error = $(this).parent().parent().find("label").text().replace("*", "")+ "不能超过" + $(this).data("max-length") + "个文字"
            }

            if($(this).data("required-error") && !$(this).val()){
                error = $(this).data("required-error")
            }

            if($(this).attr("name") == "domain" && $(this).val()){
                if(!!$(this).val() && !$(this).val().match(/\w|\d|_|-|－+/g)){
                    error = "域名只能是字母、数字、_、-"
                }else{
                    if($(this).val().match(/\w|\d|_|－|-+/g).join("") != $(this).val()){
                        error = "域名只能是字母、数字、_、-"
                    }
                }
            }

            if(!!error){
                if(["domain", "price"].includes($(this).attr("name"))){
                    $(this).parent().parent().find("span.error").text(error);
                    $(this).parent().parent().find("span.error").removeClass("hidden")
                }else{
                    $(this).parent().find("span.error").text(error);
                    $(this).parent().find("span.error").removeClass("hidden")
                }
            }
        });

        function validateAll(){
            $("input, textarea").focus();
            $("input, textarea").blur()

        }

        function silentValidateAll(){
            var error = null;
            for(var i=0; i< $(".error").length; i++){
                if(!$($(".error")[i]).hasClass("hidden")){
                    error = "请完善资料后在进行提交"
                }
            }

            if(!!error){
                $(".submit-button").addClass("disabled")
            }else{
                $(".submit-button").removeClass("disabled")
                if(!$(".zhuanlan-name").val()){
                    $(".submit-button").addClass("disabled")
                    return
                }

                if(!$(".zhuanlan-bio").val()){
                    $(".submit-button").addClass("disabled")
                    return
                }

                if(!$(".zhuanlan-specialty").val()){
                    $(".submit-button").addClass("disabled")
                    return
                }

                if(!$(".zhuanlan-domain").val()){
                    $(".submit-button").addClass("disabled")
                    return
                }
                $(".submit-button").removeClass("disabled")
            }
        }


        $(".zhuanlan-new input, .zhuanlan-new textarea").focus(function(){
            if(["domain", "price"].includes($(this).attr("name"))){
                $(this).parent().parent().find("span.error").addClass("hidden")
            }else{
                $(this).parent().find("span.error").addClass("hidden")
            }
        });

        $('.zhuanlan-bio').keyup(function(e){
            var text = $('.zhuanlan-bio').val();
            // if(text.match(/\s/)){
            //   $('.zhuanlan-bio').val(text.replace(/\s/g, ""))
            //   $('.zhuanlan-bio').focus()
            // }
            widthCheck(this, 1600, 1200, ".zhuanlan-bio-count")
        });

        $('textarea, input').blur(function(){
            setTimeout(silentValidateAll, 1)
        });

        function widthCheck(e, t, n, i) {
            for (var r = 0, o = 0; o < e.value.length; o++) {
                var a = e.value.charCodeAt(o);
                if (a >= 1 && a <= 126 || 65376 <= a && a <= 65439 ? r++ : r += 2,
                    r > t) {
                    e.value = e.value.substr(0, o);
                    break
                }
            }
            n && r < n ? $(i).text("") : $(i).text(parseInt(r / 2) + "/" + t / 2)
        }
        if(!!$(".zhuanlan-logo-img").attr("src")){
            $("svg.edit-image-div.no-logo").hide();
            $(".edit-avatar-mask").removeClass("hidden")
        }else{
            $("svg.edit-image-div").show();
            $(".edit-avatar-mask").addClass("hidden")
        }
        silentValidateAll();
        function judgeDomain(e) {
            var domain = e.value;
            // console.log(domain);
            if(domain.length>3){
                $.ajax({
                    dataType: 'json',
                    data:{
                        'domain':domain,
                        _token: $('input[name="_token"]').val()
                    },
                    type:'POST',
                    url: "/zhuanlan/judgeDomain",
                    success: function (json) {
                        // console.log(json);
                        if(!json.status == 'success'){
                            $('.zhuanlan-domain-error').html('域名已被占用');
                        }
                    }
                });
            }
        }
        $(".checkbox-item").click(function(){
            var reward_able = false;
            $(".checkbox-item").removeClass("checked");
            $(this).addClass("checked")
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
                        console.log("addedfile");
                        var res = JSON.parse(file.xhr.response);
                        console.log(JSON.parse(file.xhr.response));
                        $('.zhuanlan-logo svg').hide();
                        $('.zhuanlan-logo-img').attr('src',res.url);
                    });
                }
            });
        });
    </script>
@endsection