<!DOCTYPE html>
<html lang="en" xmlns:wb="http://open.weibo.com/wb">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="{{ config('seo.default_keywords') }}">
    <meta name="description" itemprop="description"  content="{{ isset($site_description) ? $site_description: config('seo.default_description') }}">
    <meta name="author" content="小小梦工场">
    <meta name="baidu_union_verify" content="0d05ce1487b3848a67cceabe1bb1ef07">
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
    <meta name="shenma-site-verification" content="8e1bd0b2f7d08df54038976dbf51b347_1529031177"/>
    <!-- DNS 预解析 -->
    <link rel="dns-prefetch" href="//cdn.bootcss.com" />
    <link rel="dns-prefetch" href="//cdn.bootcss.com" />
    <link rel="dns-prefetch" href="//hm.baidu.com" />
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('favicon.ico')}}">
    {{--<meta property="og:image" content="http://demo.adminlte.acacha.org/img/LaraAdmin-600x600.jpg" />--}}
    <title>{{ isset($site_title)?$site_title:config('seo.default_site_name') }}{{ isset($site_title_addon) ? ' - '.$site_title_addon: '-'.config('seo.default_sub_title') }}</title>
    @yield('base-css')
    @yield('content-css')
    {{--百度统计--}}
    @yield('baidutongji')
    {{--谷歌统计--}}
    @yield('googletongji')
</head>

<body>
<input type="hidden" name="_token" value="{{ csrf_token() }}" />
@yield('header')
@yield('content')
@yield('goTop')
@yield('base-js')
@yield('content-js')
</body>
</html>
