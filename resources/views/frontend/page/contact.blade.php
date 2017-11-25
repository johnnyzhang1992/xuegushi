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
                    联系我们
                </h1>
                <div class="ContactPage-para">
                   <p>
                       学古诗是一个关于中国传统文化的网站。网站的侧重点是收集并分类展示古诗文(包括诗词曲赋文言文)以及一些现代诗歌。学古诗收集大量的古诗文并且对其分类，主要通过形式（诗词曲文言文等）、类型（咏物，写景等）、朝代，对其进行分类。
                   </p>
                </div>
                <div class="ContactPage-para">
                    如果作品的收录侵犯了您的权益，敬请告知，以便及时撤除或作相应处理。 感谢您的支持！
                </div>
                <div class="ContactPage-para">
                    网站创建的原因，本人对中华传统文化比较喜欢，特别是去年看了中国诗词大会之后，便有了创建一个内容丰富，页面简洁的网站。
                </div>
                <div class="ContactPage-para">
                    本站由 小小梦工场 个人维护，内容收集自互联网。
                </div>
                <div class="ContactPage-para">
                    本站欢迎类似网站与本站交换链接。
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