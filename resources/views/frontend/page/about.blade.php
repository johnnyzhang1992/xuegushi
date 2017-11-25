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
                    免责声明
                </h1>
                <div class="ContactPage-para">
                    <p>
                        学古诗是一个关于中国传统文化的网站。网站的侧重点是收集并分类展示古诗文(包括诗词曲赋文言文)以及一些现代诗歌。学古诗收集大量的古诗文并且对其分类，主要通过形式（诗词曲文言文等）、类型（咏物，写景等）、朝代，对其进行分类。
                    </p>
                </div>
                <div class="ContactPage-para">
                    本站所存古诗文内容供古诗文爱好者学习、阅读或研究。如果认为内容有收藏价值，请购买正版书籍。
                </div>
                <div class="ContactPage-para">
                    本站资源完全免费，不用于任何商业用途。
                </div>
                <div class="ContactPage-para">
                    作品链接与资源主要来自网络，版权为来源网站、资源作者或原版权人所有。本站将在首页列出所有资源的来源网站。对于资源的原创人员，本站能确定的，将尽力于相关资源中列出，如不能确定的，为保证原创人员的权益，将不列出（如确为原创人员，有需要的请与本站联系），请见谅！在此，向那些在网络上提供资源的网站与个人表示感谢！
                </div>
                <div class="ContactPage-para">
                    在不侵犯他人权益的情况下，可任意复制或是转载本站资源，但决不可将本站资源用于商业用途。本站不承担由此带来的一切后果
                </div>
                <div class="ContactPage-para">
                    如果作品的收录侵犯了您的权益，敬请告知，以便及时撤除或作相应处理。 感谢您的支持！
                </div>
                <div class="ContactPage-para">
                    本站欢迎类似网站与本站交换链接。
                </div>
                <div class="ContactPage-para">
                    2017.11.25
                </div>
            </div>
            @include('frontend.partials.page_footer')
        </div>
    </main>
@endsection

@section('content-js')

@endsection