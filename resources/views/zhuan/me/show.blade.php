<?php
use App\Helpers\DateUtil;
?>
@extends('zhuan.layout.me')

@section('content-css')
    <style>
        .hero {
            text-align: center;
            height: 500px;
        }
        .hero--profile {
            height: auto;
            text-align: left;
        }
        .xzl-user-profile-header {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }
        .xzl-user-profile-header .hero-avatar, .xzl-user-profile-header .other-basic-info, .xzl-user-profile-header .user-avatar-dropzone {
            display: inline-block;
            vertical-align: top;
        }
        .xzl-user-profile-header .other-basic-info {
            margin-left: 12px;
        }
        .xzl-user-profile-header .hero-avatar, .xzl-user-profile-header .other-basic-info, .xzl-user-profile-header .user-avatar-dropzone {
            display: inline-block;
            vertical-align: top;
        }
        .hero--profile .hero-avatar {
            position: relative;
        }
        .xzl-user-profile-header .hero-avatar, .xzl-user-profile-header .other-basic-info, .xzl-user-profile-header .user-avatar-dropzone {
            display: inline-block;
            vertical-align: top;
        }
        .avatar {
            display: block;
            white-space: nowrap;
            overflow: visible;
            text-overflow: ellipsis;
            line-height: normal;
            position: relative;
        }
        .xzl-user-profile-header .user-avatar-edit .u-relative {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            position: relative;
        }
        .avatar-image {
            display: inline-block;
            vertical-align: middle;
            border-radius: 100%;
        }
        .xzl-width-80 {
            width: 80px !important;
        }
        .xzl-height-80 {
            height: 80px !important;
        }
        .xzl-user-profile-header .profile-avatar .edit-avatar-mask {
            width: 80px;
            height: 80px;
            background: #000;
            border-radius: 40px;
            opacity: 0.5;
            position: absolute;
            z-index: 900;
            display: block;
            left: 0px;
            top: 0px;
        }
        .profile-avatar .edit-image-div {
            position: absolute;
            z-index: 1000;
            left: 25px;
            top: 25px;
        }
        .xzl-user-profile-header .other-basic-info .hero-title {
            margin-top: 0px;
            line-height: 45px;
            font-weight: bold;
            font-size: 18px;
            color: #2D2D2F;
        }
        .hero--profile .hero-title {
            font-size: 32px;
            margin-left: -2px;
            margin-bottom: 0;
        }
        .xzl-user-profile-header .other-basic-info .hero-description {
            font-size: 14px;
            color: #818181;
            line-height: 23px;
            padding-bottom: 0px;
            margin-bottom: 0px;
            word-break: break-all;
        }
        .hero-description {
            color: rgba(0,0,0,0.6);
            font-size: 18px;
            outline: 0;
            word-break: break-word;
            word-wrap: break-word;
        }
        .xzl-user-profile-header .other-basic-info .user-profile-bio-count {
            font-size: 12px;
            color: #2D2D2F;
        }
        textarea {
            resize: none;
            outline: none;
            box-shadow: none !important;
            padding-top: 8px;
        }
        .xzl-user-profile-header .other-basic-info .xzl-profile-button-set {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin-top: 20px;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            font-size: 14px;
            color: #818181;
            line-height: 23px;
        }
        .xzl-button-set {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }
        .xzl-user-profile-header .other-basic-info .xzl-profile-button-set .xzl-button-chrome-less {
            padding-left: 0px;
            padding-right: 0px;
        }
        .xzl-button-set>.xzl-button-chrome-less:not(.button--circle) {
            margin-right: 0;
            padding-right: 8px;
        }
        .xzl-profile-button-set .xzl-button-chrome-less {
            color: rgba(0,0,0,0.44);
            margin-right: 6px;
        }
        .xzl-button-chrome-less, .button--link {
            border-radius: 0;
            box-shadow: none;
            height: auto;
            line-height: inherit;
            border-width: 0;
            padding: 0;
            vertical-align: baseline;
            color: rgba(0,0,0,0.44);
            white-space: normal;
            text-align: left;
        }
        .xzl-marign-right32 {
            margin-right: 32px !important;
        }
        .xzl-marign-left32 {
            margin-left: 32px !important;
        }
        .pc-third-accounts {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }
        .mobile-third-accounts {
            display: none;
        }
        .xzl-button-set .xzl-button-set-inner {
            display: inline-block;
            vertical-align: middle;
            line-height: 20px;
        }
        .home-container{
            margin-top: 60px;
            margin-bottom: 60px;
        }
        .no-write-topics, .no-comments, .no-favorites, .no-subscribes {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            margin-top: 125px;
            text-align: center;
            margin-top: 125px;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
        }
        .no-write-topics svg, .no-comments svg, .no-favorites svg, .no-subscribes svg {
            margin-left: auto;
            margin-right: auto;
        }
        .no-write-topics span, .no-comments span, .no-favorites span, .no-subscribes span {
            margin-top: 40px;
        }
        .Drafts-list {
            margin-bottom: 80px;
        }
        .Drafts-item {
            padding: 20px 0;
            margin: 0;
            border-bottom: 1px solid rgba(0,0,0,.06);
        }
        .Drafts-link, .Drafts-title {
            color: #333;
            text-decoration: none;
        }
        .Drafts-title {
            font-size: 16px;
            padding: 0 0 8px;
            font-weight: 400;
            line-height: 1.5;
            word-break: break-all;
            max-height: 60px;
            margin: 0;
            overflow: hidden;
        }
        .Drafts-meta {
            color: gray;
            text-decoration: none;
            font-size: 14px;
            display: inline;
        }
        .Drafts-updated {
            position: relative;
            cursor: pointer;
            color: gray;
        }
        .Bull {
            margin: 0 6px;
        }
        .Bull:before {
            content: "\B7";
        }
        .Drafts-removeButton {
            cursor: pointer;
        }
        ul{
            padding-left: 0;
        }
        @media (max-width: 730px){
            .mobile-third-accounts {
                display: block;
                margin-top: 20px;
            }
            .content{
                padding-left: 0;
                padding-right: 0;
            }
            ul.nav-tabs>li{
                display: inline-block;
            }
            ul.nav-tabs>li>a{
                padding: 5px 8px;
            }
        }
    </style>
@endsection

@section('top-menu')

@endsection

@yield('me_top')

@section('content')
    <main class="main_content col-md-12 no-padding">
        <div class="content col-md-9 col-xs-12">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="container-stream col-md-10 col-md-offset-1 col-xs-12">
                @include('zhuan.partials.me_top')
                <div class="home-container">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="{{ url('/people/'.@$me->domain) }}" >文章</a>
                        </li>
                        <li role="presentation">
                            <a href="{{ url('people/'.@$me->domain.'/subscribes') }}" >订阅</a>
                        </li>
                        <li role="presentation">
                            <a href="{{ url('people/'.@$me->domain.'/comments') }}" >回复</a>
                        </li>
                        <li role="presentation">
                            <a href="{{ url('people/'.@$me->domain.'/favorites') }}" >喜欢</a>
                        </li>
                        <li role="presentation">
                            <a href="{{ url('people/'.@$me->domain.'/collects') }}" >收藏</a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="poem">
                            <div class="pane-content">
                                @if(isset($posts) && count($posts)<1)
                                    <div class="no-write-topics">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="78px" height="93px" viewBox="0 0 78 93" version="1.1">
                                            <!-- Generator: Sketch 44.1 (41455) - http://www.bohemiancoding.com/sketch -->
                                            <title></title>
                                            <desc>Created with Sketch.</desc>
                                            <defs></defs>
                                            <g id="A-首页-更改" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g id="C-个人中心-01-Copy" transform="translate(-681.000000, -1011.000000)">
                                                    <g id="Group-4" transform="translate(650.000000, 987.000000)">
                                                        <g id="Group-3" transform="translate(29.000000, 20.000000)"></g>
                                                        <rect id="Rectangle-24" fill="#F5F5F5" opacity="0" x="0" y="0" width="140" height="140"></rect>
                                                        <rect id="Rectangle-60" stroke="#C7C7C7" stroke-width="3" x="32.5" y="25.5" width="75" height="90"></rect>
                                                        <rect id="Rectangle-61" fill="#FF7055" x="47" y="43" width="46" height="3"></rect>
                                                        <rect id="Rectangle-61" fill="#FF7055" x="47" y="56" width="46" height="3"></rect>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                        <span>您目前还没有写文章</span>
                                    </div>
                                @endif
                                <div class="write-topics">
                                    <div class="InfiniteList Drafts-list">
                                        <ul>
                                            @if(isset($posts) && $posts)
                                                @foreach($posts as $post)
                                                    <li class="Drafts-item">
                                                        <div class="Drafts-title">
                                                            <a class="Drafts-link" href="{{url('post/'.@$post->id)}}">{{@$post->title}}</a>
                                                        </div>
                                                        <div class="Drafts-meta">
                                                            <time class="Drafts-updated" title="">{{@ DateUtil::formatDate(strtotime($post->created_at))}}</time>
                                                            <span class="Bull"></span>
                                                            <span class="Drafts-removeButton" data-id="{{@$post->id}}">阅读 {{@$post->pv_count}}</span>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    </script>
@endsection