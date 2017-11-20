<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ LAConfigs::getByKey('site_description') }}">
    <meta name="author" content="小小梦工场">

    <meta property="og:title" content="{{ LAConfigs::getByKey('sitename') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="{{ LAConfigs::getByKey('site_description') }}" />
    
    <meta property="og:url" content="https://xuegushi.cn" />
    <meta property="og:sitename" content="学古诗" />
	<meta property="og:image" content="http://demo.adminlte.acacha.org/img/LaraAdmin-600x600.jpg" />
    
    {{--<meta name="twitter:card" content="summary_large_image" />--}}
    {{--<meta name="twitter:site" content="@laraadmin" />--}}
    {{--<meta name="twitter:creator" content="@laraadmin" />--}}
    <title>{{ LAConfigs::getByKey('sitename') }}</title>
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/la-assets/css/bootstrap.css') }}" rel="stylesheet">
	<link href="{{ asset('la-assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Custom styles for this template -->
    {{--<link href="{{ asset('/la-assets/css/main.css') }}" rel="stylesheet">--}}
    <link rel="stylesheet" href="{{ asset(elixir('css/app.css')) }}">
    <script src="{{ asset('/la-assets/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
</head>

<body data-spy="scroll" data-offset="0" data-target="#navigation">
{{--header--}}
<header id="navigation" class="navbar navbar-default">
    <div class="col-md-9 col-md-offset-2">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><b>{{ LAConfigs::getByKey('sitename') }}</b></a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#home" class="smoothScroll">推荐</a></li>
                <li><a href="#about" class="smoothScroll">诗</a></li>
                <li><a href="#contact" class="smoothScroll">词</a></li>
                <li><a href="#contact" class="smoothScroll">曲</a></li>
                <li><a href="#contact" class="smoothScroll">文言文</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    {{--<li><a href="{{ url('/login') }}">注册</a></li>--}}
                    <!--<li><a href="{{ url('/register') }}">Register</a></li>-->
                @else
                    <li class="dropdown">
                        <a role="button" class="nav-user dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><img src="{{ asset('static/images/avatar.jpg') }}" class="user-image" alt="User Image"/>{{ Auth::user()->name }}<span class="caret"></span></a>
                        <ul id="dropdown" class="dropdown-menu">
                            @if(Auth::user()->id == 1)
                                <li><a href="{{ url('admin/users/1') }}" class="btn btn-default btn-flat">个人页面</a></li>
                                <li><a href="{{ url('admin/') }}" class="btn btn-default btn-flat">后台管理</a></li>
                            @endif
                            <li><a href="https://xuegushi.cn/logout" class="btn btn-default btn-flat">登出</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</header>
{{--main-content--}}
<main class="main_content col-md-12 no-padding">
    <div class="content col-md-9 col-md-offset-2">
        {{--left--}}
        <div class="main_left col-md-8">
            @if(isset($poems) && count($poems)>0)
                @foreach($poems as $poem)
                    <div class="poem-card">
                        <div class="card-title">
                            <h2 class="poem-title">
                                <a class="title-link" href="{{ url('poem/'.$poem->id) }}" target="_blank">{{@$poem->title}}</a>
                            </h2>
                        </div>
                        <div class="poem-author">
                            <p>
                                <a class="author_dynasty" href="{{ url('/poem/'.@$poem->dynasty) }}" target="_blank">{{@$poem->dynasty}}</a> : <a class="author_name" href="{{ url('/author/'.@$poem->author) }}" target="_blank">{{@$poem->author}}</a>
                            </p>
                        </div>
                        <div class="poem-content">
                            @if(isset($poem->content) && json_decode($poem->content))
                                @if(isset(json_decode($poem->content)->xu) && json_decode($poem->content)->xu)
                                    <p class="poem-xu">{{ @json_decode($poem->content)->xu }}</p>
                                @endif
                                    @if(isset(json_decode($poem->content)->content) && json_decode($poem->content)->content)
                                        @foreach(json_decode($poem->content)->content as $item)
                                            <p class="p-content">
                                                {{@$item}}
                                            </p>
                                        @endforeach
                                    @endif
                            @endif
                        </div>
                        <div class="poem-tool clearfix">
                            <div class="collect">
                                <i class="fa fa-heart-o"></i>
                            </div>
                            <div class="copy">
                                <i class="fa fa-clone"></i>
                            </div>
                            <div class="speaker">
                                <i class="fa fa-microphone" aria-hidden="true"></i>
                            </div>
                            <div class="like pull-right">
                                <i class="fa fa-thumbs-o-up"></i> {{@$poem->like_count}}
                            </div>
                        </div>
                        <div class="tool-qrcode">

                        </div>
                        <div class="poem-tag">
                            <p>
                                @if(isset($poem->tags) && $poem->tags)
                                    @foreach(json_decode($poem->tags) as $key=>$tag)
                                        @if($key+1 < count(json_decode($poem->tags)))<a href="" class="tag">{{@$tag}} ,</a>@else<a href="" class="tag">{{@$tag}}</a>@endif
                                    @endforeach
                                @endif
                            </p>
                        </div>
                    </div>
                @endforeach
            @endif
            @if($poems->nextPageUrl())
                {{@$poems->links()}}
            @endif
        </div>
        {{--right--}}
        <div class="main_right col-md-4">
            <div class="side-card">
                <div class="side-title">
                    <h2>朝代</h2>
                </div>
                <div class="side-content">
                    <a class="" href="">先秦</a>
                    <a class="" href="">两汉</a>
                    <a class="" href="">魏晋</a>
                    <a class="" href="">南北朝</a>
                    <a class="" href="">隋代</a>
                    <a class="" href="">唐代</a>
                    <a class="" href="">五代</a>
                    <a class="" href="">宋代</a>
                    <a class="" href="">金朝</a>
                    <a class="" href="">元代</a>
                    <a class="" href="">明代</a>
                    <a class="" href="">清代</a>
                </div>
            </div>
            <div class="side-card">
                <div class="side-title">
                    <h2>作者</h2>
                </div>
                <div class="side-content">
                    <a class="" href="">先秦</a>
                    <a class="" href="">两汉</a>
                    <a class="" href="">魏晋</a>
                    <a class="" href="">南北朝</a>
                    <a class="" href="">隋代</a>
                    <a class="" href="">唐代</a>
                    <a class="" href="">五代</a>
                    <a class="" href="">宋代</a>
                    <a class="" href="">金朝</a>
                    <a class="" href="">元代</a>
                    <a class="" href="">明代</a>
                    <a class="" href="">清代</a>
                </div>
            </div>
            <footer class="footer">
                <a href="" class="footer-item">学古诗</a>
                <span class="footer-dot">&sdot;</span>
                <a href="" class="footer-item">关于</a>
                <span class="footer-dot">&sdot;</span>
                <a href="" class="footer-item">联系</a>
                <span class="footer-dot">&sdot;</span>
                <a href="" class="footer-item">反馈</a>
                <br>
                <span class="footer-item">&copy; 2017 学古诗网</span>
            </footer>
        </div>
    </div>
</main>

<script src="{{ asset('/la-assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
{{--<script>--}}

{{--</script>--}}
</body>
</html>
