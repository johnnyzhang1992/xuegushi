<?php
use App\Helpers\DateUtil;
?>
@extends('zhuan.layout.zhuanlan')

@section('content-css')
    <style>
        body {
            font-family: -apple-system,BlinkMacSystemFont,Helvetica Neue,PingFang SC,Microsoft YaHei,Source Han Sans SC,Noto Sans CJK SC,WenQuanYi Micro Hei,sans-serif;
            text-rendering: optimizeLegibility;
            line-height: 1.2;
            color: #333;
            font-size: 16px;
        }
        @media (min-width: 992px){
            .container-stream{
                margin-left: 12.5%;
            }
        }
        .ColumnAbout {
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            text-align: center;
        }
        .ColumnAbout-avatar {
            width: 100px;
            height: 100px;
            margin-bottom: 22px;
            border-radius: 50%;
        }
        .ColumnAbout-name {
            font-size: 20px;
            line-height: 28px;
            margin-bottom: 12px;
        }
        .ColumnAbout-intro {
            margin-bottom: 18px;
        }
        .ColumnAbout-actions {
            position: relative;
            margin-bottom: 18px;
        }
        .ColumnAbout-followers {
            display: block;
            margin-bottom: 20px;
            font-size: 14px;
            color: gray;
        }
        .ColumnTopicList {
            margin-bottom: 48px;
            text-align: center;
        }
        .ColumnTopicList li {
            display: inline-block;
            margin: 8px;
        }
        a {
            color: inherit;
            text-decoration: none;
        }
        .TopicTag.is-active {
            border-color: transparent;
            background: rgba(0,0,0,.06);
            cursor: default;
        }
        .TopicTag {
            display: inline-block;
            height: 30px;
            line-height: 30px;
            font-size: 14px;
            padding: 0 15px;
            border-radius: 15px;
            border: 1px solid rgba(0,0,0,.1);
            color: gray;
        }
        .ColumnTopicList-showall {
            padding: 4px 10px;
            font-size: 14px;
            color: gray;
        }
        .Menu {
            position: relative;
            border: 0;
            -webkit-box-shadow: none;
            box-shadow: none;
        }
        .ColumnAbout-menu {
            display: inline-block;
            position: absolute;
            right: 56px;
            top: 2px;
            /*padding: 0;*/
            /*padding: 0;*/
        }
        .ColumnAbout-menuButton {
            font-size: 18px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border: 1px solid currentColor;
            border-radius: 50%;
            color: #b3b3b3;
            background-color: transparent;
            -webkit-transition: -webkit-transform .2s ease-out .2s;
            transition: -webkit-transform .2s ease-out .2s;
            transition: transform .2s ease-out .2s;
            transition: transform .2s ease-out .2s,-webkit-transform .2s ease-out .2s;
        }
        .ColumnAbout-menuButton:focus {
            outline: none;
        }
        .ColumnAbout-menu .Menu-dropdown {
            top: 100%;
            left: -48px;
            margin-top: 8px;
            width: 128px;
        }
        .Menu--open>.Menu-dropdown {
            visibility: visible;
            opacity: 1;
            -webkit-transform: none;
            transform: none;
        }
        .Menu-dropdown {
            position: absolute;
            border: 1px solid rgba(0,0,0,.08);
            border-radius: 4px;
            line-height: 54px;
            font-size: 14px;
            text-align: left;
            color: #333;
            background: #fff;
            -webkit-box-shadow: 0 8px 18px rgba(0,0,0,.05);
            box-shadow: 0 8px 18px rgba(0,0,0,.05);
            -webkit-transform: translateY(-10%);
            transform: translateY(-10%);
            z-index: 100;
            -webkit-transition: all .1s;
            transition: all .1s;
        }
        .Menu-dropdown li:first-child {
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
        }
        .Menu-dropdown li {
            padding: 0;
        }
        .Menu-dropdown li:hover {
            background: #f7f8f9;
        }
        .Menu-item {
            display: block;
            width: 100%;
            padding: 0 20px;
            text-align: left;
            cursor: pointer;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }
        .Menu-dropdown li>a {
            padding: 0 16px;
            width: 100%;
            height: auto;
            line-height: 42px;
            text-align: left;
            display: block;
            color: #333;
        }
        .BlockTitle {
            position: relative;
            display: block;
            margin-bottom: 32px;
            line-height: 22px;
        }
        .BlockTitle-title {
            position: relative;
            display: inline;
            padding-right: 16px;
            font-size: 16px;
            font-weight: 600;
            font-synthesis: style;
            background: #fbfcfc;
            z-index: 1;
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
        ul{
            padding-left: 0;
        }
        .ColumnPostList .Spinner, .ColumnPostList li {
            margin-bottom: 56px;
        }
        .PostListItem {
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
            contain: layout;
            display: flex;
        }
        .PostListItem-titleImageWrapper {
            -ms-flex-negative: 0;
            flex-shrink: 0;
            padding-right: 16px;
        }
        .PostListItem-info {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }
        .PostListItem-titleImage {
            height: 180px;
            width: 240px;
            border-radius: 4px;
            vertical-align: top;
            -o-object-fit: cover;
            object-fit: cover;
        }
        .PostListItem-footer {
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            font-size: 14px;
            line-height: 1.5;
            color: gray;
        }
        .PostListItem-title {
            line-height: 1.5;
            font-size: 20px;
            font-weight: 600;
            font-synthesis: style;
        }
        .PostListItem-summary {
            margin: auto;
            padding-top: 10px;
            padding-bottom: 10px;
            line-height: 1.7;
            color: #666;
            word-break: break-all;
        }
        .PostListItem-readall {
            padding-left: 4px;
            color: #b3b3b3;
        }
        .PostListItem-readall .icon {
            display: inline-block;
            vertical-align: middle;
            font-size: 16px;
            speak: none;
            font-style: normal;
            font-weight: 400;
            -webkit-font-feature-settings: normal;
            font-feature-settings: normal;
            font-variant: normal;
            text-transform: none;
            line-height: 1;
            margin-left: 3px;
        }
        .PostListItem-footer {
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            font-size: 14px;
            line-height: 1.5;
            color: gray;
        }
        .Bull {
            margin: 0 6px;
        }
        .Bull:before {
            content: "\B7";
        }
        .PostListItem-date {
            display: inline;
        }
        .HoverTitle {
            position: relative;
        }
        .ColumnEnd {
            position: relative;
            display: block;
            margin: 30px auto 80px;
            text-align: center;
            color: rgba(0,0,0,.1);
        }
        .ColumnEnd:before {
            content: "";
            position: absolute;
            top: 50%;
            width: 340px;
            height: 1px;
            left: 50%;
            margin-left: -170px;
            background: currentColor;
        }
        .ColumnEnd-icon {
            position: relative;
            padding: 0 12px;
            background: #fff;
            z-index: 1;
        }
        @media (max-width: 660px){
            .content,.container-stream{
                padding: 0;
            }
            .BlockTitle {
                margin-left: 16px;
                margin-right: 16px;
            }
            .PostListItem--responsive {
                -webkit-box-orient: vertical;
                -webkit-box-direction: normal;
                -ms-flex-direction: column;
                flex-direction: column;
            }
            .PostListItem {
                margin-left: 16px;
                margin-right: 16px;
            }
            .PostListItem--responsive .PostListItem-titleImageWrapper {
                padding-right: 0;
                padding-bottom: 10px;
            }
            .PostListItem--responsive .PostListItem-titleImage {
                width: 100%;
                height: 220px;
            }
        }
    </style>
@endsection

@section('content')
    <main class="main_content col-md-12 no-padding">
        <div class="content col-md-9 col-xs-12">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="container-stream col-md-9 col-md-offset-1 col-xs-12">
                <div class="ColumnAbout">
                    <img src="{{ asset($data->avatar) }}" class="ColumnAbout-avatar" alt="">
                    <h1 class="ColumnAbout-name">{{@$data->alia_name}}</h1>
                    <p class="ColumnAbout-intro">{{@$data->about}}</p>
                    <div>
                        <div class="ColumnAbout-actions">
                            @if(isset($data->verified) && $data->verified)
                                <button class="Button ColumnFollowButton btn btn-success" type="button">关注专栏</button>
                            @else
                                <button class="Button ColumnFollowButton btn btn-success" type="button">审核中</button>
                            @endif
                            <div class="Menu ColumnAbout-menu">
                                <button class="MenuButton  ColumnAbout-menuButton down"><i class="fa fa-angle-down "></i></button>
                                <div class="Menu-dropdown" style="display: none">
                                    <ul class="Menu-list">
                                        <li class="Menu-item">
                                            <a href="{{url(@$data->name.'/about')}}">关于</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <a class="ColumnAbout-followers" href="{{ url($data->name.'/followers') }}" target="_blank">41,543人关注</a>
                    </div>
                    {{--<ul class="ColumnTopicList">--}}
                        {{--<li>--}}
                            {{--<a href="{{ url($data->name) }}"><span class="TopicTag is-active">全部<span class="TopicTag-count">61</span></span></a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="/keepitreal?topic=%E5%BF%83%E7%90%86%E5%AD%A6"><span class="TopicTag">心理学<span class="TopicTag-count">14</span></span></a>--}}
                        {{--</li>--}}
                        {{--<li><button class="ColumnTopicList-showall" type="button">更多</button></li>--}}
                    {{--</ul>--}}
                </div>
                <div class="BlockTitle av-marginLeft av-borderColor">
                    <h2 class="BlockTitle-title">最新文章</h2><span class="BlockTitle-line"></span>
                </div>
                <div class="InfiniteList ColumnPostList">
                    <ul>
                        @if(isset($posts) && $posts)
                            @foreach($posts as $post)
                                <li>
                                    <div class="PostListItem PostListItem--responsive">
                                        <a class="PostListItem-titleImageWrapper" href="{{ url('/post/'.@$post->id) }}">
                                            <img src="{{ asset(@$post->cover_url) }}" class="PostListItem-titleImage" alt="题图">
                                        </a>
                                        <di class="PostListItem-info">
                                            <a href="{{ url('/post/'.@$post->id) }}">
                                                <span class="PostListItem-title">{{  @$post->title}}</span>
                                                <p class="PostListItem-summary">{{@$post->content}}
                                                    <span class="PostListItem-readall">查看全文<i class="icon icon-ic_unfold fa fa-angle-right"></i></span>
                                                </p>
                                            </a>
                                            <div class="PostListItem-footer">
                                                <span><a class="PostListItem-name" target="_blank" href="{{ url('/people/'.@$post->creator_id) }}">{{@$post->author_name}}</a>
                                                    <span class="Bull"></span>
                                                    <div class="HoverTitle PostListItem-date" data-hover-title="2016 年 6月 18 日星期六凌晨 12 点 03 分"><time datetime="2016-06-18T00:03:42+08:00">{{@ DateUtil::formatDate(strtotime($post->created_at))}}</time>
                                                    </div>
                                                </span>
                                                <span>
                                                    {{--<a href="{{ url('/post/'.@$post->id) }}">赞</a>--}}
                                                    {{--<span class="Bull"></span>--}}
                                                    {{--<a href="{{ url('/post/'.@$post->id.'#comments') }}">条评论</a>--}}
                                                </span>
                                            </div>
                                        </di>
                                    </div>
                                </li>
                            @endforeach
                        @endif

                    </ul>
                    <div class="ColumnEnd">
                        <i class="ColumnEnd-icon icon-ic_column_end">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" t="1515483616017" class="icon" style="" viewBox="0 0 1024 1024" version="1.1" p-id="503" width="16" height="16">
                                <defs><style type="text/css"/></defs>
                                <path d="M572.965533 1023.994909c-5.218344-27.417435-10.527309-54.81756-15.639759-82.253322-22.411897-120.257123-57.166578-236.776384-105.568375-349.172898-0.549835-1.277858-1.153636-2.532297-1.911187-4.191985-1.54463 0.539653-3.04344 0.903155-4.400719 1.559903-41.017712 19.852108-82.933489 38.084239-122.761928 60.093942-36.841001 20.359179-71.830889 44.187411-106.914453 67.570684-29.420261 19.607737-57.8498 40.708175-86.555274 61.373836-2.471204 1.778819-4.655272 5.024884-5.395513 7.983812-15.24062 60.926841-23.537023 122.680471-21.308154 185.62032 0.461251 13.016841 1.140399 26.024519 1.753364 39.798911-6.672353 0.39303-13.004623 0.765697-20.051678 1.180109-0.836971-10.058931-1.64136-19.724832-2.444731-29.390732 0-20.517002 0-41.034004 0-61.551006 0.316664-1.741145 0.848172-3.474144 0.919447-5.225472 1.701435-42.335281 10.518145-83.564781 18.229076-125.066145 9.969328-53.65476 32.695853-100.828591 65.725681-143.715743 25.206893-32.729455 52.700694-63.352225 82.263505-92.155449 1.789001-1.743181 4.056563-2.993548 6.102153-4.473012-0.469396-0.555945-0.938793-1.111889-1.407171-1.667834-19.844981 1.076252-39.176782-1.817511-58.377234-6.586823-44.530549-11.061871-75.787667-37.056862-90.22797-81.083395-14.188805-43.263891-14.766132-87.500176-4.05249-131.444234 8.870676-36.386877 30.805031-65.462982 56.932389-91.498701 6.647916-6.625515 13.751991-12.793853 21.132003-19.620974-5.466788-3.89263-10.440761-7.935956-15.873948-11.21664-22.919986-13.839558-28.16684-41.893376-22.733653-62.428706 7.194697-27.193428 26.300455-42.437102 52.033764-50.35473 23.691792-7.28939 48.054586-6.290523 72.380724-4.44552 18.030525 1.367461 36.064104 2.73594 54.070191 4.393591 12.278637 1.130217 20.306232-3.703242 26.224089-14.684675 6.745664-12.516898 14.497324-24.596983 22.835474-36.127233 13.42209-18.559996 30.808086-32.126672 53.400207-38.07813 4.242896-1.117998 8.554012-1.978389 12.834581-2.956892 7.643729 0 15.287457 0 22.931186 0 12.275582 2.825542 24.730369 5.043211 36.791108 8.599831 27.161863 8.008249 51.773101 21.036291 72.202537 40.87109 36.928567 35.853334 48.058659 79.174245 35.012289 128.581018-16.487931 62.435833-42.49514 121.101222-72.061005 178.290201-1.089488 2.106684-2.235997 4.182821-4.292788 8.020468 3.514873-1.563976 5.395513-2.350037 7.233389-3.224682 26.891018-12.801998 54.399074-23.609316 83.788788-29.591321 41.256993-8.397207 75.818213 3.594294 105.003266 32.770183 32.863859 32.852658 52.34941 73.232969 64.425422 117.504892 7.169241 26.284163 21.050546 38.030274 48.215464 38.063875 11.562832 0.014255 23.336435-1.127162 34.646751-3.498582 18.818622-3.944559 32.361879-16.393237 43.855473-31.063657 18.089581-23.089009 29.394805-49.703073 38.640184-77.198911 6.128627-18.22704 11.209512-36.805363 16.958346-55.885666 6.22943 1.217783 12.815235 2.504805 19.402058 3.791827 0 1.206583 0 2.414184 0 3.620767-3.205336 10.910158-6.349579 21.839661-9.627209 32.728436-10.2249 33.960475-22.382369 67.10536-42.529759 96.672244-15.081778 22.133925-33.735449 40.066701-60.468645 47.154485-18.445956 4.890479-37.343998 6.156119-56.207421 2.892745-28.214696-4.881315-44.949035-22.232692-52.104021-49.542196-9.696447-37.005951-25.370826-71.077411-50.241708-100.473235-4.78764-5.658212-10.188244-10.853138-15.678451-15.852566-24.632621-22.43328-53.38697-28.124075-85.283526-20.507838-42.27317 10.09355-81.082377 28.034472-117.300231 51.838267-1.502883 0.987667-3.086205 1.851112-5.161324 3.087223 1.253421 1.055887 2.286908 2.130103 3.504691 2.920236 9.270834 6.017642 18.282023 12.51588 27.92756 17.863538 35.440957 19.646429 59.989066 49.23775 78.170286 84.674634 20.456927 39.872222 29.488481 82.614788 31.399668 127.115809 1.293131 30.120792 6.106226 59.578726 18.272859 87.404464 22.032104 50.389349 61.197686 78.78936 114.71906 88.138596 26.979603 4.712292 53.964297 5.698941 80.537633-2.911072 28.048727-9.088574 51.377016-25.304642 71.092684-46.993607 1.199455-1.318586 2.385674-2.649392 3.918086-4.351844 5.393477 4.030089 10.623021 7.937992 16.188576 12.096376-1.539539 1.980425-2.643282 3.612621-3.954741 5.056448-42.62649 46.931496-94.932117 67.753962-158.414673 59.383229-31.804917-4.193003-61.594789-13.405799-87.817859-32.34457-35.777986-25.839204-55.777735-61.875816-66.681783-103.790574-6.732428-25.880951-7.367793-52.435958-9.736157-78.848416-4.968882-55.414233-22.254074-106.161993-60.856584-147.616519-9.316653-10.004966-20.854031-18.216858-32.246821-25.965462-42.376009-28.819515-85.44644-56.623871-127.643243-85.698957-63.067126-43.457352-125.652636-87.615235-188.30433-131.673332-4.314171-3.033258-7.15295-3.094351-10.68106 0.533544-13.710245 14.101239-28.426484 27.341069-41.094078 42.316953-29.737943 35.156875-38.511889 77.097089-35.426702 121.873028 1.139381 16.532732 3.420179 33.271144 7.691585 49.244877 9.701538 36.286074 32.825167 60.383115 69.388195 71.119158 27.011168 7.930865 54.111938 12.245035 81.872511 4.311116 4.977028-1.422444 9.751431-3.553565 15.060396-5.520754 3.867175 5.022847 7.892173 10.251373 11.881533 15.433062-13.357943 12.594283-26.735232 24.775171-39.630906 37.446838-37.524222 36.872565-72.849103 75.698064-100.564874 120.658299-9.755503 15.826092-17.203735 33.077684-25.627415 49.718347-0.86039 1.699398-1.187237 3.668623-1.147527 6.182592 95.769088-71.741286 196.256578-135.12304 308.384284-179.864359-13.824285-31.938303-27.039678-62.468416-40.612463-93.826337 5.987095-2.719648 12.072957-5.485116 17.60593-7.998067 84.144145 175.923873 145.394777 358.595446 172.219612 552.537625-4.441447 1.039596-9.78605 2.29098-15.129634 3.542365C575.379717 1023.994909 574.172116 1023.994909 572.965533 1023.994909zM477.772753 402.641325c1.890822-3.338722 3.257265-6.447328 5.236672-9.097738 3.932341-5.2662 8.949078-9.846124 12.21958-15.464627 37.441747-64.331747 67.674542-131.856611 86.579711-203.967509 11.397882-43.474661-0.937775-80.719893-35.068291-110.118771-25.7272-22.160398-56.279714-33.625482-89.597696-38.355084-27.071242-3.842738-49.535068 4.708219-67.321221 25.335188-4.710256 5.462715-9.072282 11.298097-12.992404 17.353413-6.439182 9.945909-12.404895 20.198301-18.53454 30.342762-7.065383 11.693164-17.710805 17.279083-31.064675 17.606948-8.625286 0.211788-17.291302-0.072293-25.901315-0.66693-19.24729-1.329787-38.447741-3.478217-57.710304-4.385446-19.16176-0.902137-38.245117 0.263717-56.022106 8.745436-12.887528 6.148991-22.426152 15.444262-26.66192 29.470153-4.79884 15.891258-0.428668 32.335406 10.997724 40.30089 100.247191 69.885083 200.526965 139.723328 300.801648 209.568701C474.500215 400.539733 476.32689 401.687259 477.772753 402.641325z" p-id="504"/>
                            </svg>
                        </i>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('content-js')
    <script src="{{ asset('lib/jquery-sticky/jquery.sticky.js') }}"></script>
    <script>
        $("#navigation").sticky({
            topSpacing:0,
            zIndex:999
        });
        $('.MenuButton').on('click',function () {
            if($(this).hasClass('down')){
                $(this).removeClass('down').addClass('up');
                $(this).find('.fa').removeClass('fa-angle-down').addClass('fa-angle-up');
            }else{
                $(this).removeClass('up').addClass('down');
                $(this).find('.fa').removeClass('fa-angle-up').addClass('fa-angle-down');
            }
            $('.Menu-dropdown').toggle();
        })
    </script>
@endsection