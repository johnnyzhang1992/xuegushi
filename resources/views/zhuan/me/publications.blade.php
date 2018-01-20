<?php
use App\Helpers\DateUtil;
?>
@extends('zhuan.layout.me')

@section('content-css')
    <style>
        .home-container{
            margin-top: 20px;
            margin-bottom: 60px;
        }
        .no-write-topics {
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
        .profile-zhuanlan-header {
            border-bottom: 1px solid #EDEDED;
            position: relative;
        }
        .hot-zhuanlan-header-title {
            font-weight: bold;
            font-size: 16px;
            color: #2D2D2F;
            min-width: 64px;
            line-height: 35px;
        }
        .hot-zhuanlan-header-title {
            font-weight: bold;
            font-size: 16px;
            color: #2D2D2F;
            min-width: 64px;
            line-height: 35px;
        }
        .hot-zhuanlan-header-title:after {
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 1px;
            width: 64px;
            content: '';
            background: #2D2D2F 100%;
        }
        .main_content .content {
            padding-top: 20px;
        }
        .zhuanlan-list-header {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            width: inherit;
            height: 45px;
            margin-top: 40px;
            margin-bottom: 40px;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
        }
        .zhuanlan-list-header span.tip {
            font-size: 32px;
            color: #2D2D2F;
            line-height: 45px;
            font-weight: bold;
        }
        .xzl-button-set {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }
        .zhuanlan-list-header .publication-new-btn {
            border: 1px solid #C7C7C7;
            height: 32px;
            line-height: 32px;
            border-radius: 100px;
            padding: 0 13px;
        }
        .zhuanlan-list-header .publication-new-btn span {
            font-size: 14px;
            color: #C7C7C7;
            line-height: 20px;
        }
        @media (max-width: 730px){
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
                <div class="home-container">
                    <div class="js-profileHeaderBlock">
                        <div class="zhuanlan-list-header">
                            <span class="tip">专栏申请</span>
                            <div class="item submit-item xzl-button-set">
                                <a class="button xzl-base-color-normal-btn publication-new-btn link-btn" href="{{ url('/apply') }}">
                                    <span class="button-label  xzl-default-state-btn  js-buttonLabel">申请专栏</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="myPublications">
                        <div class="profile-zhuanlan-header u-block">
                            <span class="hot-zhuanlan-header-title"><p>我的专栏</p></span>
                        </div>
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
                                <span>您目前还没有创建任何专栏</span>
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
                                                <span class="subscribe-count">审核状态: @if(isset($zl->verified) && $zl->verified) 已审核 @else 未通过审核 @endif</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="mySubscribes" style="margin-top: 20px">
                        <div class="profile-zhuanlan-header u-block">
                            <span class="hot-zhuanlan-header-title"><p>订阅专栏</p></span>
                        </div>
                        @if(isset($subs) && count($subs)<1)
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
                            @if(isset($subs) && $subs)
                                @foreach($subs as $zl)
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