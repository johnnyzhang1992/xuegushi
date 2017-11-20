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
                    <li>
                        <a href="{{ url(config('laraadmin.adminRoute')) }}" class="nav-user"><img src="{{ asset('static/images/avatar.jpg') }}" class="user-image" alt="User Image"/>{{ Auth::user()->name }}</a>
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
            <div class="poem-card">
                <div class="card-title">
                    <h2 class="poem-title">
                        <a class="title-link" href="" target="_blank">水调歌头·明月几时有</a>
                    </h2>
                </div>
                <div class="poem-author">
                    <p><a class="author_dynasty" href="">宋代</a> : <a class="author_name" href="">苏轼</a></p>
                </div>
                <div class="poem-content">
                    <p class="poem-xu">丙辰中秋，欢饮达旦，大醉，作此篇，兼怀子由。</p>
                    <p class="p-content">
                        明月几时有？把酒问青天。不知天上宫阙，今夕是何年。我欲乘风归去，又恐琼楼玉宇，高处不胜寒。起舞弄清影，何似在人间？(何似 一作：何时；又恐 一作：惟 / 唯恐)
                    </p>
                    <p class="p-content">
                        转朱阁，低绮户，照无眠。不应有恨，何事长向别时圆？人有悲欢离合，月有阴晴圆缺，此事古难全。但愿人长久，千里共婵娟。(长向 一作：偏向)
                    </p>
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
                        <i class="fa fa-thumbs-o-up"></i> 1000
                    </div>
                </div>
                <div class="tool-qrcode">

                </div>
                <div class="poem-tag">
                    <p>
                        <a href="" class="tag">宋词三百首</a>，<a href="" class="tag">宋词精选</a>，<a href="" class="tag">初中古诗</a>，<a href="" class="tag">豪放</a>，<a href="" class="tag">中秋节</a>
                    </p>
                </div>
            </div>
            <div class="poem-card">
                <div class="card-title">
                    <h2 class="poem-title">
                        <a class="title-link" href="" target="_blank">水调歌头·明月几时有</a>
                    </h2>
                </div>
                <div class="poem-author">
                    <p><a class="author_dynasty" href="">宋代</a> : <a class="author_name" href="">苏轼</a></p>
                </div>
                <div class="poem-content">
                    <p class="poem-xu">丙辰中秋，欢饮达旦，大醉，作此篇，兼怀子由。</p>
                    <p class="p-content">
                        明月几时有？把酒问青天。不知天上宫阙，今夕是何年。我欲乘风归去，又恐琼楼玉宇，高处不胜寒。起舞弄清影，何似在人间？(何似 一作：何时；又恐 一作：惟 / 唯恐)
                    </p>
                    <p class="p-content">
                        转朱阁，低绮户，照无眠。不应有恨，何事长向别时圆？人有悲欢离合，月有阴晴圆缺，此事古难全。但愿人长久，千里共婵娟。(长向 一作：偏向)
                    </p>
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
                        <i class="fa fa-thumbs-o-up"></i> 1000
                    </div>
                </div>
                <div class="tool-qrcode">

                </div>
                <div class="poem-tag">
                    <p>
                        <a href="" class="tag">宋词三百首</a>，<a href="" class="tag">宋词精选</a>，<a href="" class="tag">初中古诗</a>，<a href="" class="tag">豪放</a>，<a href="" class="tag">中秋节</a>
                    </p>
                </div>
            </div>
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
