<?php
use App\Helpers\DateUtil;
?>
@extends('zhuan.layout.zhuanlan')

@section('content-css')
    <link href="{{ asset('lib/summernote/dist/summernote.css') }}" rel="stylesheet" type="text/css">
    <style>
        .container-stream {
            padding-bottom: 30px;
        }
        @media(min-width:768px){
            .container-stream {
                margin-left: 12.5%;
            }
        }
        section{
            overflow: hidden;
            clear: both;
            min-height: 40px;
            margin-bottom: 15px;
        }
        .topic-show {
            width: 100%;
        }
        .topic-title {
            margin-top: 40px;
            font-weight: bold;
            font-size: 24px;
            color: #2D2D2F;
            line-height: 32px;
        }
        .topic-header {
            margin-top: 24px;
        }
        .xzl-flex-center {
            display: -ms-flexbox !important;
            display: -webkit-box !important;
            display: flex !important;
            -ms-flex-align: center !important;
            -webkit-box-align: center !important;
            align-items: center !important;
        }
        .u-flex0 {
            -ms-flex: 0 0 auto;
            -webkit-box-flex: 0;
            flex: 0 0 auto;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }
        .link {
            color: inherit;
            text-decoration: none;
            cursor: pointer;
        }
        .avatar {
            display: block;
            white-space: nowrap;
            overflow: visible;
            text-overflow: ellipsis;
            line-height: normal;
            position: relative;
        }
        .avatar-image {
            width: 48px;
            height: 48px;
        }
        .avatar-image {
            display: inline-block;
            vertical-align: middle;
            border-radius: 100%;
        }
        .xzl-author-lockup {
            font-size: 14px;
            line-height: 1.4;
            padding-left: 10px;
            text-rendering: auto;
        }
        .u-noWrapWithEllipsis {
            white-space: nowrap !important;
            text-overflow: ellipsis !important;
            overflow: hidden !important;
        }
        .u-flex1 {
            -ms-flex: 1 1 auto;
            -webkit-box-flex: 1;
            flex: 1 1 auto;
        }
        .xzl-author-lockup {
            font-size: 14px;
            line-height: 1.4;
            padding-left: 10px;
            text-rendering: auto;
        }
        .u-noWrapWithEllipsis {
            white-space: nowrap !important;
            text-overflow: ellipsis !important;
            overflow: hidden !important;
        }
        .topic-header .xzl-author-lockup-header {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }
        .topic-header .xzl-author-lockup-time {
            padding-left: 0px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            height: 20px;
            line-height: 20px;
            margin-top: 7px;
        }
        .topic-header .xzl-author-lockup-user a.user-name {
            font-size: 14px;
            color: #2D2E2F;
            line-height: 20px;
        }

        .link--accent {
            color: #FF7055;
            text-decoration: none;
        }
        .topic-show-partial-main .topic-header .xzl-author-lockup-time span.time {
            margin-left: 20px;
            font-size: 14px;
            color: #C7C7C7;
            display: inline-block;
        }
        span.time,span.views-count,span.likes-count,span.collects-count{
            font-size: 14px;
            color: #C7C7C7;
            display: inline-block;
            margin-right: 5px;
        }
        .time {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }
        abbr.timeago[title] {
            cursor: initial;
            text-decoration: none;
        }
        .time .timeago {
            border-bottom: none;
        }
        .topic-body {
            margin-top: 50px;
            line-height: 28px;
            margin-bottom: 30px;
        }
        .BlockTitle {
            margin-bottom: 12px;
        }
        .BlockTitle {
            position: relative;
            display: block;
            margin-bottom: 15px;
            line-height: 22px;
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
        ul.Contributes-list{
            padding-left: 0;
        }
        .Contributes-listItem {
            position: relative;
            padding: 16px 0;
        }
        .ContributesItem {
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            display: flex;
        }
        .ContributesItem-avatar {
            margin-right: 12px;
        }
        .ContributesItem-info {
            -ms-flex-item-align: stretch;
            align-self: stretch;
            font-size: 14px;
            -webkit-box-flex: 0;
            -ms-flex: 0 1 auto;
            flex: 0 1 auto;
            overflow: hidden;
        }
        .ContributesItem-entrance {
            margin-left: auto;
            font-size: 14px;
            color: #50c87e;
            min-width: 60px;
            text-align: center;
        }
        .ContributesItem-avatar img {
            width: 48px;
            height: 48px;
            vertical-align: top;
            border-radius: 7px;
        }
        .Avatar-hemingway {
            width: 36px;
            height: 36px;
            border-radius: 50%;
        }
        .ContributesItem-nameLine {
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            line-height: 1.4;
        }
        .ContributesItem-intro {
            margin-top: 6px;
            color: gray;
            line-height: 1.4;
        }
        .u-ellipsis {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: block;
        }
        .ContributesItem-name {
            font-size: 16px;
            font-weight: 600;
            font-synthesis: style;
        }
        p{
            text-align: justify;
        }
        .PostIndex-footer{
            margin-bottom: 20px;
        }
        .PostIndex-vote,.PostIndex-control,.Fav,.PostShare{
            display: inline-block;
        }
        .btn .fa{
            margin-right: 5px;
        }
        .PostIndex-control .btn{
            line-height: inherit;
            background-color: transparent;
            border: none;
            border-radius: 0;
            color: gray;
            font-size: 18px;
        }
        .Button-green {
            color: #50c87e;
            background-color: #50c87e;
            border-color: #50c87e;
        }
        .Button-green:hover ,.Button-green:focus,.Button-green:active{
            background-color: rgba(77,190,46,.06);
            color: #50c87e;
            border-color: #50c87e;
        }
        .PostIndex-voteButton{
            background-color: transparent;
        }
        .PostIndex-voteButton.active{
            color: #fff;
            background-color: #50c87e;
        }

        .btn:focus,.btn:active,.btn.active:focus,.btn:active{
            outline: 5px auto #f1f2f3;
            box-shadow: none;
        }
        .PostIndex-control .btn{
            color: gray;
            background: transparent;
        }
        .PostIndex-control .ColButton.active{
            outline: none;
            box-shadow: none;
            color: #50c87e;
            background-color: #fff;
        }
        .PostShare .Menu-dropdown {
            top: 100%;
            width: 134px;
            left: 50%;
            margin-left: -66px;
        }
        .Menu{
            position: relative;
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
            visibility: hidden;
            opacity: 0;
            -webkit-transition: all .1s;
            transition: all .1s;
        }
        ul{
            padding-left: 0;
        }
        .PostShare .Menu-item:first-child {
            border-top: 0;
        }
        .Menu-dropdown li:last-child {
            border-bottom-left-radius: 4px;
            border-bottom-right-radius: 4px;
        }
        .PostShare .Menu-item {
            border-top: 1px solid rgba(0,0,0,.08);
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
            width: 100%;
            height: auto;
            line-height: 42px;
            text-align: left;
            display: block;
            color: #333;
        }
        .Menu-dropdown li:hover {
            background: #f7f8f9;
        }
        .PostShare-qrCode {
            width: 99px;
            height: 99px;
        }
        .IconWeibo {
            color: #e16a70;
        }
        .IconWechat {
            color: #81d300;
        }
        i.fa{
            margin-right: 5px;
        }
        @media(max-width: 768px){
            .content{
                padding-left: 0;
                padding-right: 0;
            }
            .breadcrumb{
                display: none;
            }
        }
    </style>
@endsection
@include('zhuan.partials.review')

@section('content')
    <main class="main_content col-md-12 no-padding clearfix">
        <div class="content col-md-9 col-md-offset-1 col-xs-12 zhuanlan-new">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="container-stream col-md-9  col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('/post')}}">文章</a></li>
                    <li class="active">{{@$post->title}}</li>
                </ol>
                @if(isset($data->cover_url) && $data->cover_url != '')
                    <div class="xzl-aspect-ratio-placeholder-fill">
                        <img class="topic-show" src="{{asset(@$post->cover_url)}}" alt="{{@$post->title}}">
                    </div>
                @endif

                <div class="topic-title">{{@$post->title}}</div>
                <div class="topic-header">
                    <div class="postMetaInline">
                        <div class="xzl-flex-center">
                            <div class="post-meta-inline-avatar u-flex0">
                                <a class="link avatar xzl-link-color" href="{{url('/people/'.@$post->creator_id)}}">
                                    <img class="avatar-image" src="{{ asset(@$post->avatar) }}" alt="0bb4d03eb491f8b3be5091267493affc">
                                </a>
                            </div>
                            <div class="xzl-author-lockup xzl-author-lockup-user u-flex1 u-noWrapWithEllipsis">
                                <div class="xzl-author-lockup-header">
                                    <a class="link link link--darken user-name link--accent u-accentColor--textNormal xzl-text-darken xzl-link-color" href="{{url('/people/'.@$post->creator_id)}}">{{@$post->user_name}}</a>
                                </div>
                                <div class="xzl-author-lockup xzl-author-lockup-time">
                                    <span class="time"><abbr class="timeago" title="2018-01-04T21:53:35+08:00">{{@ DateUtil::formatDate(strtotime($post->created_at))}}</abbr></span>
                                    <span class="views-count">阅读 {{@$post->pv_count}}</span>
                                    <span class="likes-count">喜欢 {{@$post->like_count}}</span>
                                    <span class="collects-count">收藏 {{@$post->collect_count}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="topic-body">
                    {!! @$post->content !!}
                </div>
                <div class="PostIndex-footer">
                    <div class="PostIndex-vote">
                        <button class="btn PostIndex-voteButton Button-green btn-large FavButton @if(isset($is_like) && $is_like) active @endif " type="button"><i class="fa fa-thumbs-o-up"></i><span class="like_count">{{number_format($post->like_count)}}</span></button>
                    </div>
                    <div class="PostIndex-control pull-right">
                        <div class="Fav">
                            @if(isset($is_collect) && $is_collect)
                                <button class="btn Button--plain ColButton active" type="button"><i class="fa fa-star"></i><span class="collect-name">收藏</span></button>
                            @else
                                <button class="btn Button--plain ColButton" type="button"><i class="fa fa-star-o"></i><span class="collect-name">收藏</span></button>
                            @endif
                        </div>
                        <div class="PostShare">
                            <div class="Menu Menu--open">
                                <button class="btn shareButton MenuButton-listen-click" type="button"><i class="fa fa-share-alt"></i>分享</button>
                                <div class="Menu-dropdown" style="display: none">
                                    <ul class="Menu-list">
                                        <li class="Menu-item PostShare-sina">
                                            <a class=" WeiboShareButton PostShare-button" type="button"><i class="fa fa-weibo IconWeibo"></i>微博分享</a>
                                        </li>
                                        <li class="Menu-item PostShare-wechat">
                                            <a class=" WechatShareButton PostShare-button" type="button"><i class="fa fa-weixin IconWechat"></i>微信扫一扫 {!! QrCode::size(100)->margin(0)->generate(url('/post/'.$post->id)) !!}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if(isset($post->zhuan_name))
                <div class="topic-footer">
                    <div class="topic-contributes">
                        <div class="BlockTitle av-marginLeft av-borderColor">
                            <span class="BlockTitle-title">文章被以下专栏收录</span>
                            <span class="BlockTitle-line"></span>
                        </div>
                        <ul class="Contributes-list">
                            <li class="Contributes-listItem av-borderColor av-marginLeft" style="opacity: 1; max-height: 300px;">
                                <div class="ContributesItem av-paddingRight" role="link">
                                    <a class="ContributesItem-avatar" href="{{ url(@$post->zhuan_name)}}"><img class="Avatar-hemingway" src="{{@$post->zhuan_avatar}}"></a>
                                    <div class="ContributesItem-info">
                                        <div class="ContributesItem-nameLine">
                                            <a class="ContributesItem-name" href="{{ url(@$post->zhuan_name)}}">{{@$post->zhuan_alia_name}}</a>
                                        </div>
                                        <p class="ContributesItem-intro u-ellipsis">{{@$post->about}}</p>
                                    </div>
                                    <a class="ContributesItem-entrance" href="{{ url(@$post->zhuan_name)}}">进入专栏</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                @endif
                @yield('review')
            </div>
        </div>
    </main>
    @yield('review-modal')
@endsection

@section('content-js')
    <script src="{{ asset('lib/jquery-sticky/jquery.sticky.js') }}"></script>
    <script>
        $("#navigation").sticky({
            topSpacing:0,
            zIndex:999
        });
        $('.shareButton').on('click',function () {
            $(this).next().toggle();
        });
        // 喜欢
        $('.FavButton').on('click',function () {
            var like = $(this).find('.like_count');
            $.post(
                '/post/{{@$post->id}}/like',
                {
                    '_token': $('input[name="_token"]').val()
                },
                function (res) {
                    if(res && res.status == 'active'){
                        $(like).html(res.num);
                        $(like).parent().addClass('active');
                        $('body').toast({
                            position:'fixed',
                            content:res.msg,
                            duration:1000,
                            isCenter:true,
                            background:'rgba(51,122,183,0.8)',
                            animateIn:'bounceIn-hastrans',
                            animateOut:'bounceOut-hastrans'
                        });
                    }else if(res && res.status == 'delete'){
                        $(like).html(res.num);
                        if($(like).hasClass('active')){
                            $(like).removeClass('active')
                        }
                        $('body').toast({
                            position:'fixed',
                            content:res.msg,
                            duration:1000,
                            isCenter:true,
                            background:'rgba(51,122,183,0.8)',
                            animateIn:'bounceIn-hastrans',
                            animateOut:'bounceOut-hastrans'
                        });
                    }else{
                        $('body').toast({
                            position:'fixed',
                            content:res.msg,
                            duration:1000,
                            isCenter:true,
                            background:'rgba(0,0,0,0.5)',
                            animateIn:'bounceIn-hastrans',
                            animateOut:'bounceOut-hastrans'
                        });
                    }
                }
            )
        });
        // 收藏
        $('.ColButton').on('click',function () {
            var th = $(this);
            $.post(
                '/post/{{@$post->id}}/collect',
                {
                    '_token': $('input[name="_token"]').val()
                },
                function (res) {
                    if(res && res.status == 'active'){
                        $(th).addClass('active');
                        $(th).find('i').removeClass('fa-star-o');
                        $(th).find('i').addClass('fa-star');
                        if($(th).find('.collect-name')){
                            $(th).find('.collect-name').html('已收藏');
                        }
                        $('body').toast({
                            position:'fixed',
                            content:res.msg,
                            duration:1000,
                            isCenter:true,
                            background:'rgba(51,122,183,0.8)',
                            animateIn:'bounceIn-hastrans',
                            animateOut:'bounceOut-hastrans'
                        });
                    }else if(res && res.status == 'delete'){
                        $(th).removeClass('active');
                        $(th).find('i').removeClass('fa-star');
                        $(th).find('i').addClass('fa-star-o');
                        if($(th).find('.collect-name')){
                            $(th).find('.collect-name').html('收藏');
                        }
                        $('body').toast({
                            position:'fixed',
                            content:res.msg,
                            duration:1000,
                            isCenter:true,
                            background:'rgba(51,122,183,0.8)',
                            animateIn:'bounceIn-hastrans',
                            animateOut:'bounceOut-hastrans'
                        });
                    }else{
                        $('body').toast({
                            position:'fixed',
                            content:res.msg,
                            duration:1000,
                            isCenter:true,
                            background:'rgba(0,0,0,0.5)',
                            animateIn:'bounceIn-hastrans',
                            animateOut:'bounceOut-hastrans'
                        });
                    }
                }
            )
        });
    </script>
    @yield('review-js')
@endsection