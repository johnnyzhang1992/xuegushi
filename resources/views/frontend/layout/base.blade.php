<!DOCTYPE html>
<html lang="en" xmlns:wb="http://open.weibo.com/wb">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="{{ config('seo.default_keywords') }}">
    <meta name="description" itemprop="description"  content="{{ isset($site_description) ? $site_description: config('seo.default_description') }}">
    <meta name="author" content="小小梦工场">
    {{--og meta--}}
    <meta property="og:title" content="{{ isset($site_title)?$site_title:config('seo.default_site_name') }}{{ isset($site_title_addon) ? '  -  '.$site_title_addon: '-'.config('seo.default_sub_title') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="{{ isset($site_description) ? $site_description: config('seo.default_description') }}" />
    <meta property="og:url" content="https://xuegushi.cn" />
    <meta property="og:sitename" content="学古诗" />
    <meta property="og:image" content="{{ asset('/static/images/avatar.png')}}">
    <meta itemprop="name" content="{{ isset($site_title)?$site_title:config('seo.default_site_name') }}"/>
    <meta itemprop="image" content="{{ asset('/static/images/avatar.png')}}" />
    <meta name="sogou_site_verification" content="qZUdNCxRqM"/>
    <meta name="360-site-verification" content="692a791faf2667be55acfff23615caaf" />
    <meta name="msvalidate.01" content="5C4998A61A6BA963E7C7B3F6C148F865" />
    <meta name="shenma-site-verification" content="8e1bd0b2f7d08df54038976dbf51b347_1522558437">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('favicon.ico')}}">
    {{--<meta property="og:image" content="http://demo.adminlte.acacha.org/img/LaraAdmin-600x600.jpg" />--}}
    <title>{{ isset($site_title)?$site_title:config('seo.default_site_name') }}{{ isset($site_title_addon) ? ' - '.$site_title_addon: '-'.config('seo.default_sub_title') }}</title>
    @yield('base-css')
    @yield('content-css')
    {{--百度统计--}}
    @yield('baidutongji')
    {{--谷歌统计--}}
    @yield('googletongji')
    <script src="https://tjs.sjs.sinajs.cn/open/api/js/wb.js" type="text/javascript" charset="utf-8"></script>
</head>

<body>
<input type="hidden" name="_token" value="{{ csrf_token() }}" />
{{--<div class="alert alert-warning alert-dismissible fade in" role="alert" style="text-align: center;margin-bottom: 0;padding-top: 5px;padding-bottom: 5px">--}}
    {{--<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>--}}
    {{--<strong>提示！</strong> 本站仍在建设中，如果您有更好的建议可以 <a href="{{ url('contact') }}">联系我们</a>--}}
{{--</div>--}}
@yield('header')
@yield('content')
@yield('goTop')
@yield('base-js')
@yield('content-js')
{{--<script>--}}
    {{--(function(){--}}
        {{--var bp = document.createElement('script');--}}
        {{--var curProtocol = window.location.protocol.split(':')[0];--}}
        {{--if (curProtocol === 'https') {--}}
            {{--bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';--}}
        {{--}--}}
        {{--else {--}}
            {{--bp.src = 'http://push.zhanzhang.baidu.com/push.js';--}}
        {{--}--}}
        {{--var s = document.getElementsByTagName("script")[0];--}}
        {{--s.parentNode.insertBefore(bp, s);--}}
    {{--})();--}}
{{--</script>--}}
<script>
    (function(){
        var src = (document.location.protocol == "http:") ? "http://js.passport.qihucdn.com/11.0.1.js?0ebc1d49bcf7137a3307e026661f57fe":"https://jspassport.ssl.qhimg.com/11.0.1.js?0ebc1d49bcf7137a3307e026661f57fe";
        document.write('<script src="' + src + '" id="sozz"><\/script>');
    })();
</script>
</body>
</html>
