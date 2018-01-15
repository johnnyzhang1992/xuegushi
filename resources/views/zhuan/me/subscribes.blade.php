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
        ul{
            padding-left: 0;
        }
        .xzl-zhuanlans-list .zhuanlan {
            padding: 0px;
            margin-top: 25px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }
        .zhuanlan {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            width: 100%;
            margin-top: 20px;
        }
        .zhuanlan .zl-body {
            margin-left: 12px;
            vertical-align: top;
            margin-right: 30px;
        }
        .zhuanlan .zl-header .avatar {
            width: 80px;
            height: 80px;
        }
        .zhuanlan .zl-body .zl-title {
            line-height: 25px;
            font-weight: bold;
            font-size: 18px;
            color: #2D2D2F;
        }
        .zhuanlan .zl-body .zl-title a {
            font-weight: bold;
            font-size: 18px;
            color: #2D2D2F;
        }
        .zhuanlan .zl-body .zl-others span {
            font-size: 14px;
            color: #818181;
            line-height: 23px;
        }
        .zhuanlan .zl-body .zl-others span.subscribe-count {
            margin-left: 31px;
        }
        .zhuanlan .zl-body .zl-bio {
            margin-top: 4px;
            font-size: 14px;
            color: #818181;
            line-height: 23px;
        }
        .zhuanlan .zl-body .zl-others {
            margin-top: 2px;
            font-size: 14px;
            color: #818181;
            line-height: 23px;
        }
        @media (max-width: 730px){
            .mobile-third-accounts {
                display: block;
                margin-top: 20px;
            }
            ul.nav-tabs>li{
                display: inline-block;
            }
            ul.nav-tabs>li>a{
                padding: 5px 8px;
            }
            .zhuanlan .zl-body {
                margin-right: 0px;
            }
            .content{
                padding-left: 0;
                padding-right: 0;
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
                        <li role="presentation">
                            <a href="{{ url('/people/'.@$me->id) }}" >文章</a>
                        </li>
                        <li role="presentation" class="active">
                            <a href="{{ url('people/'.@$me->id.'/subscribes') }}" >订阅</a>
                        </li>
                        <li role="presentation">
                            <a href="{{ url('people/'.@$me->id.'/comments') }}" >回复</a>
                        </li>
                        <li role="presentation">
                            <a href="{{ url('people/'.@$me->id.'/favorites') }}" >喜欢</a>
                        </li>
                        <li role="presentation">
                            <a href="{{ url('people/'.@$me->id.'/collects') }}" >收藏</a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="poem">
                            <div class="pane-content">
                                @if(isset($zls) && count($zls)<1)
                                    <div class="no-write-topics">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="104px" height="88px" viewBox="0 0 104 88" version="1.1">
                                            <!-- Generator: Sketch 44.1 (41455) - http://www.bohemiancoding.com/sketch -->
                                            <title></title>
                                            <desc>Created with Sketch.</desc>
                                            <defs></defs>
                                            <g id="A-首页-更改" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g id="C-个人中心-01-Copy" transform="translate(-419.000000, -1419.000000)">
                                                    <g id="Group-9" transform="translate(401.000000, 1393.000000)">
                                                        <rect id="Rectangle-24" fill="#F5F5F5" opacity="0" x="0" y="0" width="140" height="140"></rect>
                                                        <g id="Group-5" transform="translate(18.000000, 26.000000)">
                                                            <path d="M102.5,79.5 L102.5,1.5 L1.5,1.5 L1.5,79.5 L45.7218254,79.5 L52,85.7781746 L58.2781746,79.5 L102.5,79.5 Z" id="Combined-Shape" stroke="#C7C7C7" stroke-width="3"></path>
                                                            <rect id="Rectangle-64" stroke="#C7C7C7" stroke-width="3" x="52.5" y="9.5" width="1" height="62"></rect>
                                                            <rect id="Rectangle" fill="#C7C7C7" x="59" y="25" width="36" height="3"></rect>
                                                            <rect id="Rectangle-Copy-2" fill="#C7C7C7" x="9" y="25" width="36" height="3"></rect>
                                                            <rect id="Rectangle-Copy" fill="#C7C7C7" x="59" y="35" width="36" height="3"></rect>
                                                            <rect id="Rectangle-Copy-3" fill="#C7C7C7" x="9" y="35" width="36" height="3"></rect>
                                                            <rect id="Rectangle-Copy-3" fill="#C7C7C7" x="9" y="45" width="14" height="3"></rect>
                                                            <polygon id="icon_mark" fill="#FF7055" points="87 3 97 3 97 16.3333333 92 14.6673642 87 16.3333333"></polygon>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                        <span>您目前还没有订阅任何专栏</span>
                                    </div>
                                @endif
                                <div class="write-topics">
                                    @if(isset($zls) && $zls)
                                        @foreach($zls as $zl)
                                            <div class="zhuanlan">
                                                <div class="zl-header">
                                                    <a href="{{url(@$zl->name)}}">
                                                        <img class="avatar" src="{{asset(@$zl->avatar)}}" alt="38a91dd14991f96cfa5c00aeb4667263">
                                                    </a>
                                                </div>
                                                <div class="zl-body">
                                                    <div class="zl-title"><a href="{{url(@$zl->name)}}">{{@$zl->alia_name}}</a></div>
                                                    <div class="zl-bio">{{@$zl->about}}</div>
                                                    <div class="zl-others">
                                                        <span>已发表 {{@$zl->post_count}}</span>
                                                        <span class="subscribe-count">订阅数 {{@$zl->followers_count}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
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