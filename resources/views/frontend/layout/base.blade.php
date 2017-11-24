<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="{{ config('seo.default_keywords') }}">
    <meta name="description" content="{{ isset($site_description) ? $site_description: config('seo.default_description') }}">
    <meta name="author" content="小小梦工场">
    {{--og meta--}}
    <meta property="og:title" content="{{ isset($site_title)?$site_title:config('seo.default_site_name') }}{{ isset($site_title_addon) ? ' - '.$site_title_addon: '-'.config('seo.default_sub_title') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="{{ isset($site_description) ? $site_description: config('seo.default_description') }}" />
    <meta property="og:url" content="https://xuegushi.cn" />
    <meta property="og:sitename" content="学古诗" />
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
@yield('header')
@yield('content')
@yield('goTop')
@yield('base-js')
@yield('content-js')
</body>
</html>
