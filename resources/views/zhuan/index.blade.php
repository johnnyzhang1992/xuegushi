@extends('zhuan.layout.base')

@section('baidutongji')
    @include('zhuan.partials.baidutongji')
@endsection

{{--@section('googletongji')--}}
    {{--@include('frontend.partials.googletongji')--}}
{{--@endsection--}}

@section('base-css')
    @include('zhuan.partials.base_css')
@endsection

@section('header')
    @include('zhuan.partials.header')
@endsection

@section('content')
    {{--main-content--}}
    <main class="main_content col-md-12 no-padding clearfix">
        <div class="content col-md-9 col-xs-12">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="HomeTop">
                <h1>古诗<span class="Bull"></span><br>专栏</h1>
                <h2>分享关于古诗词的一切</h2>
                <p><a href="{{url('/write')}}" class="btn btn-success">开始写文章</a></p>
                @if(isset($is_has) && $is_has)
                @else
                    <p><a href="{{url('/apply')}}" class="apply-btn">申请开通专栏</a><svg width="12" height="12" viewBox="0 0 6 10" class="Icon Icon--arrowRight" aria-hidden="true" style="fill: rgb(51, 200, 126); height: 12px; width: 12px;"><title></title><g><path d="M.218 9.78c.29.294.76.294 1.052 0l4.512-4.25c.29-.293.29-.768 0-1.062L1.27.22C.98-.073.51-.073.218.22c-.29.295-.29.77 0 1.064L4 5 .218 8.716c-.29.294-.29.77 0 1.063z" fill-rule="evenodd"></path></g></svg></p>
                @endif
            </div>
            <section class="zhuanlan HomeColumn">
                <div class="BlockTitle av-marginLeft av-borderColor Home-blockTitle">
                    <h3 class="BlockTitle-title">专栏 · 发现</h3>
                    <span class="BlockTitle-line"></span>
                </div>
                <ul class="HomeColumn-List clearfix">
                    @if(isset($zhuans) && $zhuans)
                        @foreach($zhuans as $zhuan)
                            <li class="col-md-3 col-xs-12">
                                <p class="HomeColumn-avatar"><a target="_blank" href="{{ url('/'.@$zhuan->name) }}"><img class="Avatar-hemingway" alt="专栏头像" src="{{ asset(@$zhuan->avatar) }}" ></a></p>
                                <p class="HomeColumn-title"><a target="_blank" href="{{ url('/'.@$zhuan->name) }}">{{ @$zhuan->alia_name }}</a></p>
                                <p class="HomeColumn-description"><a target="_blank" href="{{ url('/'.@$zhuan->name) }}">{{ @$zhuan->about }}</a></p>
                                <p class="HomeColumn-meta">{{@$zhuan->follow_count}}人关注<span> | </span>{{@$zhuan->post_count}}篇文章</p>
                                <a class="Button HomeColumn-btn Button--green" target="_blank" href="{{ url('/'.@$zhuan->name) }}" type="button">进入专栏</a>
                            </li>
                        @endforeach
                    @endif
                </ul>
                <p class="Home-random"><button class="Button Refresh-button-wrapper" type="button"><i class="fa fa-refresh Refresh-button"></i>换一换</button></p>
            </section>
            <section class="posts HomeArticle">
                <div class="BlockTitle av-marginLeft av-borderColor Home-blockTitle">
                    <h3 class="BlockTitle-title">文章 · 发现</h3>
                    <span class="BlockTitle-line"></span>
                </div>
                <ul class="HomeArticle-List clearfix">
                    @if(isset($posts) && $posts)
                        @foreach($posts as $post)
                            <li class="col-md-4 col-xs-12">
                                <a target="_blank" href="{{ url('/post/'.@$post->id) }}">
                                    <p class="HomeArticle-titleImage" style="background-image: url({{ asset(@$post->cover_url) }});"></p>
                                    <p class="HomeArticle-title">{{  @$post->title}}</p>
                                </a>
                                <p class="HomeArticle-meta clearfix">
                                    <span class="HomeArticle-metaAuthor"><a target="_blank" href="{{ url('/people/'.$post->creator_id) }}">{{ @$post->author_name }}</a></span>
                                </p>
                            </li>
                        @endforeach
                    @endif
                </ul>
                <p class="Home-random"><button class="Button Refresh-button-wrapper" type="button"><i class="fa fa-refresh Refresh-button"></i>换一换</button></p>
            </section>
        </div>
    </main>
@endsection

@section('content-css')
    <style>
        .main_content{
            overflow: hidden;
        }
        .main_content .content{
            padding-bottom: 50px;
        }
        .HomeTop {
            position: relative;
            height: 220px;
            text-align: center;
            z-index: 0;
            /*background: url(//static.zhihu.com/hemingway/patterns.c72a480….png) 50% no-repeat;*/
        }
        .HomeColumn {
            margin-top: 48px;
            width: 1020px;
            margin-left: auto;
            margin-right: auto;
            position: relative;
        }
        a.apply-btn{
            color: #33c87e;;
        }
        .HomeTop h2 {
            font-size: 18px;
            line-height: 24px;
            font-weight: 300;
            margin: 0 0 20px 0;
            width: 100%;
            color: #2a2a2a;
            letter-spacing: .5em;
        }
        @media screen and (min-width: 1020px){
            .Home-blockTitle {
                margin: 0 auto 30px;
                text-align: center;
            }
            .HomeArticle-List {
                margin-left: 45px;
            }
            .HomeColumn-List li:nth-child(4n-3) {
                     margin-left: 74px;
            }
        }
        .Home-blockTitle {
            width: 500px;
        }
        .BlockTitle {
            position: relative;
            display: block;
            margin-bottom: 32px;
            line-height: 22px;
        }
        .Home-blockTitle .BlockTitle-title {
            padding-left: 16px;
            padding-right: 16px;
            font-size: 14px;
        }
        .BlockTitle-title {
            position: relative;
            display: inline;
            padding-right: 16px;
            font-weight: 600;
            font-synthesis: style;
            background: #fbfcfc;
            z-index: 1;
        }
        .Home-blockTitle .BlockTitle-line {
            color: #d9d9d9;
        }
        .BlockTitle-line {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            top: 50%;
            height: 1px;
            color: #f0f0f0;
            background: currentColor;
        }
        .HomeColumn-List li:nth-child(-n+4) {
            margin-top: 0;
        }
        .HomeColumn-List li:nth-child(4n-3) {
            margin-left: 0;
        }
        .HomeColumn-List li {
            padding: 0;
            float: left;
            list-style: none;
            margin: 20px 0 0 16px;
            width: 206px;
            /*height: 258px;*/
            text-align: center;
            color: gray;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            -webkit-box-shadow: 0 8px 18px rgba(0,0,0,.06);
            box-shadow: 0 8px 18px rgba(0,0,0,.06);
            border-radius: 4px;
            background: #fff;
            font-size: 14px;
            position: relative;
        }
        .HomeColumn-avatar, .HomeColumn-avatar img {
            width: 48px;
            height: 48px;
        }
        .HomeColumn-avatar {
            margin: 26px auto 8px;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            border-radius: 50%;
            overflow: hidden;
        }
        .HomeColumn-avatar, .HomeColumn-avatar img {
            width: 48px;
            height: 48px;
        }
        .Avatar-hemingway {
            width: 36px;
            height: 36px;
            border-radius: 50%;
        }
        .HomeColumn-title {
            margin: 16px 16px 7px;
            color: #333;
            font-size: 16px;
            font-weight: 600;
            font-synthesis: style;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .HomeColumn-title a {
            color: #333;
        }
        .HomeColumn-description {
            line-height: 21px;
            height: 42px;
            margin-bottom: 14px;
            word-break: break-all;
            padding-left: 16px;
            padding-right: 16px;
        }
        .HomeColumn-description a {
            color: gray;
        }
        .HomeColumn-meta {
            margin-bottom: 15px;
        }
        .HomeColumn-btn {
            margin-bottom: 23px;
            padding: 0;
            min-height: 0;
            min-width: 0;
            line-height: 30px;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            width: 90px;
            height: 32px;
        }
        .Home-random .Button,.HomeColumn-btn{
            border-radius: 4px;
            border-color: #b3b3b3;
            color: gray;
        }
        .Home-random .Button,.HomeColumn-btn{
            display: inline-block;
            padding: 0 16px;
            font-size: 14px;
            line-height: 32px;
            color: #8590a6;
            text-align: center;
            cursor: pointer;
            background: none;
            border: 1px solid #ccd8e1;
            border-radius: 3px;
        }
        .Button--green {
            color: #50c87e;
            border: 1px solid #50c87e;
        }
        .main_content {
            background-color: #fbfcfc;
        }
        .content{
            padding-left: 0;
            padding-right: 0;
        }
        .Home-random {
            text-align: center;
            margin: 36px 0 0;
        }
        .Refresh-button {
            padding-right: 5px;
            line-height: 0;
            display: inline-block;
        }
        .HomeArticle{
            margin: 48px auto 0;
            width: 1020px;
            position: relative;
        }
        .HomeArticle-List {
            width: auto;
            margin: 0 auto;
        }
        .HomeArticle-List li:nth-child(-n+3) {
            margin-top: 0;
        }
        .HomeArticle-List li:nth-child(3n-2) {
            margin-left: 0;
        }
        .HomeArticle-List li {
            padding-right: 0;
            padding-left: 0;
            width: 300px;
            height: 248px;
            float: left;
            list-style: none;
            margin: 20px 0 0 16px;
            text-align: center;
            color: gray;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            -webkit-box-shadow: 0 8px 18px rgba(0,0,0,.06);
            box-shadow: 0 8px 18px rgba(0,0,0,.06);
            border-radius: 4px;
            background: #fff;
            font-size: 14px;
            position: relative;
        }
        .HomeArticle-titleImage {
            width: 100%;
            height: 130px;
            background-position: 50%;
            background-size: cover;
            position: relative;
            border-radius: 4px 4px 0 0;
            margin: 0;
        }
        .HomeArticle-title {
            padding: 20px 16px 16px;
            line-height: 25px;
            text-align: left;
            color: #333;
            font-size: 16px;
            font-weight: 600;
            font-synthesis: style;
        }
        .HomeArticle-title {
            padding: 20px 16px 16px;
            line-height: 25px;
            text-align: left;
            color: #333;
            font-size: 16px;
            font-weight: 600;
            font-synthesis: style;
        }
        .HomeArticle-metaAuthor {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            float: left;
            width: 30%;
        }
        .HomeArticle-metaAuthor a{
            color: gray;
        }
        .HomeArticle-meta {
            position: absolute;
            left: 16px;
            right: 16px;
            bottom: 16px;
            text-align: left;
            line-height: 16px;
            margin-bottom: 0;
        }
        @media (max-width: 420px){
            .HomeColumn,.HomeArticle{
                width: 100%;
            }
            .HomeColumn-List,.HomeArticle-List{
                padding-left: 0;
            }
            .HomeColumn-List li {
                padding-left: 0;
                padding-right: 0;
                height: 88px;
                display: block;
                width: 100%;
                margin: 0!important;
                background: #fbfcfc;
                -webkit-box-shadow: none;
                box-shadow: none;
            }
            .HomeArticle-List li {
                background: #fbfcfc;
                -webkit-box-shadow: none;
                box-shadow: none;
                margin: 23px 16px!important;
                width: 100%;
                height: 88px;
            }
            .BlockTitle {
                margin-left: 16px;
                margin-right: 16px;
                margin-bottom: 16px;
            }
            .HomeColumn-avatar {
                float: left;
                margin: 20px 12px 20px 16px;
            }
            .HomeColumn-title {
                margin: 20px 52px 2px 12px;
                text-align: left;
                width: 136px;
            }
            .HomeColumn-description {
                margin: 2px 2px 22px 12px;
                line-height: 20px;
                text-align: left;
                width: 144px;
                overflow: hidden;
                text-overflow: ellipsis;
                padding: 0;
            }
            .HomeColumn-meta {
                display: none;
            }
            .HomeColumn-btn {
                position: absolute;
                top: 28px;
                right: 16px;
                border: none;
            }
            .HomeArticle-titleImage {
                width: 88px;
                height: 88px;
                border-radius: 4px;
            }
            .HomeArticle-title {
                position: absolute;
                top: 0;
                left: 104px;
                padding: 0;
                width: 184px;
                height: 44px;
            }
            .HomeArticle-metaAuthor {
                position: absolute;
                left: 88px;
            }
        }
    </style>
@endsection
@section('content-js')
    <script src="{{ asset('lib/jquery-sticky/jquery.sticky.js') }}"></script>
    <script>
        $("#navigation").sticky({
            topSpacing:0,
            zIndex:999
        });
        </script>
@endsection
@section('goTop')
    @include('frontend.partials.goTop')
@endsection

@section('base-js')
    @include('frontend.partials.base_js')
@endsection