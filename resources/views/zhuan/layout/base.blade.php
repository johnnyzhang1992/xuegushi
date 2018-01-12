<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="{{ config('seo.zhuanlan_keywords') }}">
    <meta name="description" content="{{ isset($site_description) ? $site_description: config('seo.zhuanlan_description') }}">
    <meta name="author" content="小小梦工场">
    {{--og meta--}}
    <meta property="og:title" content="{{ isset($site_title)?$site_title:config('seo.zhuanlan_site_name') }}{{ isset($site_title_addon) ? ' - '.$site_title_addon: '-'.config('seo.zhuanlan_sub_title') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="{{ isset($site_description) ? $site_description: config('seo.zhuanlan_description') }}" />
    <meta property="og:url" content="https://xuegushi.cn" />
    <meta property="og:sitename" content="学古诗" />
    <meta name="baidu-site-verification" content="EtwIgzlgfz" />
    <meta name="sogou_site_verification" content="qZUdNCxRqM"/>
    <meta name="360-site-verification" content="692a791faf2667be55acfff23615caaf" />
    <meta name="msvalidate.01" content="5C4998A61A6BA963E7C7B3F6C148F865" />
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('favicon.ico')}}">
    {{--<meta property="og:image" content="http://demo.adminlte.acacha.org/img/LaraAdmin-600x600.jpg" />--}}
    <title>{{ isset($site_title)?$site_title:config('seo.zhuanlan_site_name') }}{{ isset($site_title_addon) ? ' - '.$site_title_addon: '-'.config('seo.zhuanlan_sub_title') }}</title>
    @yield('base-css')
    @yield('content-css')
    {{--百度统计--}}
    @yield('baidutongji')
    {{--谷歌统计--}}
    {{--@yield('googletongji')--}}
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
</body>
</html>
