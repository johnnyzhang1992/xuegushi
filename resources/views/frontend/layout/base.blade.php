<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ LAConfigs::getByKey('site_description') }}">
    <meta name="author" content="小小梦工场">
    {{--og meta--}}
    <meta property="og:title" content="{{ LAConfigs::getByKey('sitename') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="{{ LAConfigs::getByKey('site_description') }}" />
    <meta property="og:url" content="https://xuegushi.cn" />
    <meta property="og:sitename" content="学古诗" />
    <meta property="og:image" content="http://demo.adminlte.acacha.org/img/LaraAdmin-600x600.jpg" />
    <title>{{ LAConfigs::getByKey('sitename') }}</title>
    @yield('base-css')
    @yield('content-css')
    {{--百度统计--}}
    @yield('baidutongji')
    {{--谷歌统计--}}
    @yield('googletongji')
</head>

<body>
@yield('header')
@yield('content')
@yield('goTop')
@yield('base-js')
@yield('content-js')
</body>
</html>
