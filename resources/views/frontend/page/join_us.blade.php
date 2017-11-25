@extends('frontend.layout.poem')

@section('content-css')
    <style>
        .main_content{
            background-color: #fff;
        }
        .ContactPage{
            padding-bottom: 2em;
            min-height: 340px;
        }
        .ContactPage-title{
            font-weight: 700;
            font-size: 18px;
            outline: 0;
            margin: 0;
            padding-top: 15px;
        }
        .ContactPage-para {
            margin-top: 2em;
        }
    </style>
@endsection

@section('header')
    {{--header--}}
    @include('frontend.partials.header')
@endsection

@section('content')
    {{--main-content--}}
    <main class="main_content col-md-12 no-padding">
        <div class="content col-md-9">
            <div class="ContactPage col-md-12">
                <h1 class="ContactPage-title">
                    加入我们
                </h1>
                <div class="ContactPage-para">
                    <p>
                        学古诗是一个关于中国传统文化的网站。网站的侧重点是收集并分类展示古诗文(包括诗词曲赋文言文)以及一些现代诗歌。学古诗收集大量的古诗文并且对其分类，主要通过形式（诗词曲文言文等）、类型（咏物，写景等）、朝代，对其进行分类。
                    </p>
                </div>
                <div class="ContactPage-para">
                    本站由 小小梦工场 个人维护，内容收集自互联网。网站还在逐渐完善中，公众号暂时还未开始运营，小程序在开发中。
                </div>
                <div class="ContactPage-para">
                    由于网站目前由一个人运营维护，现在又在创建初期。一个人能力有限，如果你也喜欢古诗词并且有闲暇时间，那么欢迎你加入我们。<br><br>
                    如果你拥有以下能力就更好不过了：
                    <ul>
                        <li>新媒体运营能力（运营微博以及公众号）</li>
                        <li>数据采集（python）</li>
                        <li>web开发（小程序开发，网站开发（php））</li>
                        <li>ui设计</li>
                    </ul>
                    主要是参与内容的分类整理，网站的维护，程序的开发以及内容的运营。
                </div>
                <div class="ContactPage-para">
                    网站架构：
                    <ul>
                        <li>服务器端语言：PHP(框架-laravel)</li>
                        <li>服务器：阿里云ECS</li>
                        <li>数据库：PostgreSQL</li>
                        <li>数据采集：Python</li>
                    </ul>
                </div>
                <div class="ContactPage-para">
                    请通过以下方式联系我们：）
                </div>
                <div class="ContactPage-para">
                    EMail：<a href="mailto:me@johnnyzhang.cn">me@johnnyzhang.cn</a>
                </div>
            </div>
            @include('frontend.partials.page_footer')
        </div>
    </main>
@endsection

@section('content-js')

@endsection