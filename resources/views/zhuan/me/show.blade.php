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
        @media (max-width: 730px){
            .mobile-third-accounts {
                display: block;
                margin-top: 20px;
            }
        }
    </style>
@endsection

@section('top-menu')

@endsection

@section('content')
    <main class="main_content col-md-12 no-padding">
        <div class="content col-md-9 col-xs-12">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="container-stream col-md-9 col-md-offset-2 col-xs-12">
                <header class="hero hero--profile xzl-user-profile-header">
                    <div class="user-avatar-dropzone dz-clickable">
                        <div class="hero-avatar xzl-pull-left user-avatar-edit u-relative show-avatar">
                            <div class="avatar profile-avatar">
                                <div class="u-relative">
                                    <img class="avatar-image .xzl-size-80x80 xzl-width-80 xzl-height-80 js-profileAvatarImage" src="{{ asset(@$me->avatar) }}" alt="{{@$me->name}}">
                                    <div class="edit-avatar-mask hidden"></div>
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="27px" viewBox="0 0 30 27" version="1.1" class="edit-image-div hidden">
                                        <!-- Generator: Sketch 44.1 (41455) - http://www.bohemiancoding.com/sketch -->
                                        <title>icon_photo_1</title>
                                        <desc>Created with Sketch.</desc>
                                        <defs></defs>
                                        <g id="切图" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g transform="translate(-396.000000, -110.000000)" id="icon_photo_1">
                                                <g transform="translate(393.000000, 107.000000)">
                                                    <polygon id="Shape" points="0 0 36 0 36 36 0 36"></polygon>
                                                    <circle id="Oval" fill="#FFFFFF" cx="18" cy="18" r="4.8"></circle>
                                                    <path d="M13.5,3 L10.755,6 L6,6 C4.35,6 3,7.35 3,9 L3,27 C3,28.65 4.35,30 6,30 L30,30 C31.65,30 33,28.65 33,27 L33,9 C33,7.35 31.65,6 30,6 L25.245,6 L22.5,3 L13.5,3 L13.5,3 Z M18,25.5 C13.86,25.5 10.5,22.14 10.5,18 C10.5,13.86 13.86,10.5 18,10.5 C22.14,10.5 25.5,13.86 25.5,18 C25.5,22.14 22.14,25.5 18,25.5 L18,25.5 Z" id="Shape" fill="#FFFFFF"></path>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                        </div></div>
                    <div class="other-basic-info">
                        <p class="profile-name hero-title">{{@$me->name}}</p>
                        <div class="hero-description user-profile-bio " id="user-profile-bio" data-text="完善自我介绍，方面大家了解你">{{@$me->about}}</div>
                        <div class="user-profile-bio-count hidden">100 / 140</div>
                        <textarea class="user-profile-bio-edit hidden" id="user-profile-bio-edit" placeholder="完善自我介绍，方面大家了解你(140字以内">时间会告诉你答案。</textarea>
                        <div class="xzl-button-set xzl-profile-button-set">
                            <a class="button xzl-button-chrome-less xzl-base-color-normal-btn xzl-marign-right32 no-border" href="/u/5775582212/following">
                                关注 0
                            </a>
                            <a class="button xzl-button-chrome-less xzl-base-color-normal-btn xzl-marign-right32 no-border" href="/u/5775582212/followers">
                                被关注 0
                            </a>
                            <a class="button xzl-button-chrome-less xzl-base-color-normal-btn  no-border" href="/u/5775582212/favorites">
                                获得赞 0
                            </a>
                            {{--<div class="pc-third-accounts">--}}
                                {{--<a class="button xzl-button-chrome-less xzl-marign-left32  profile-third-account-logo  weibo  button--withIcon button--withSvgIcon" href="http://weibo.com/u/2477183313" target="_blank">--}}
                                    {{--<span class="xzl-default-state-btn">--}}
                                        {{--<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="22px" height="19px" viewBox="0 0 22 19" version="1.1">--}}
                                            {{--<!-- Generator: Sketch 44.1 (41455) - http://www.bohemiancoding.com/sketch -->--}}
                                            {{--<title></title>--}}
                                            {{--<desc>Created with Sketch.</desc>--}}
                                            {{--<defs></defs>--}}
                                            {{--<g id="切图" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">--}}
                                                {{--<g transform="translate(-204.000000, -229.000000)" id="icon_mine_weibo" fill="#818181">--}}
                                                    {{--<g transform="translate(204.000000, 229.000000)">--}}
                                                        {{--<path d="M8.682,19.0015 C4.414,19.0015 0,16.813 0,13.1495 C0,11.311 1.0825,9.18 3.0485,7.1505 C4.9335,5.2065 7.08,3.9985 8.6505,3.9985 C9.277,3.9985 9.8005,4.1975 10.1655,4.5745 C10.5325,4.9525 10.884,5.654 10.6315,6.9015 C11.564,6.536 12.4405,6.3435 13.185,6.3435 C14.3855,6.3435 14.9715,6.8355 15.2505,7.2475 C15.6545,7.8425 15.6855,8.628 15.345,9.5845 C17.019,10.137 18.014,11.3335 18.014,12.796 C18.014,15.731 14.1815,19.0015 8.682,19.0015 L8.682,19.0015 Z M8.6505,4.9985 C7.3805,4.9985 5.418,6.143 3.767,7.8465 C1.9825,9.688 1,11.5715 1,13.1495 C1,15.535 3.8735,18.0015 8.682,18.0015 C13.844,18.0015 17.014,14.97 17.014,12.796 C17.014,11.327 15.6035,10.7175 14.9975,10.523 C14.788,10.4585 14.4975,10.368 14.3525,10.0685 C14.2055,9.764 14.3255,9.4535 14.365,9.3515 C14.547,8.8775 14.7055,8.225 14.423,7.809 C14.1625,7.424 13.6055,7.3435 13.185,7.3435 C12.485,7.3435 11.6205,7.5575 10.6845,7.9615 C10.681,7.963 10.677,7.9645 10.6735,7.966 C10.548,8.019 10.3615,8.08 10.174,8.08 C9.8615,8.08 9.694,7.916 9.622,7.8175 C9.528,7.69 9.4385,7.466 9.5445,7.1255 C9.8005,6.276 9.7655,5.5985 9.4475,5.2705 C9.2725,5.0895 9.0045,4.9985 8.6505,4.9985 L8.6505,4.9985 Z M21.51,6 C21.234,6 21.01,5.7765 21.01,5.5 C21.01,3.0185 18.9915,1 16.51,1 C16.234,1 16.01,0.7765 16.01,0.5 C16.01,0.2235 16.234,0 16.51,0 C19.543,0 22.01,2.4675 22.01,5.5 C22.01,5.7765 21.7865,6 21.51,6 L21.51,6 Z M18.01,6 C17.734,6 17.51,5.7765 17.51,5.5 C17.51,4.949 17.0615,4.5 16.51,4.5 C16.234,4.5 16.01,4.2765 16.01,4 C16.01,3.7235 16.234,3.5 16.51,3.5 C17.613,3.5 18.51,4.397 18.51,5.5 C18.51,5.7765 18.2865,6 18.01,6 L18.01,6 Z" id="Shape-Copy-3"></path>--}}
                                                    {{--</g>--}}
                                                {{--</g>--}}
                                            {{--</g>--}}
                                        {{--</svg>--}}

                                    {{--</span>--}}
                                {{--</a>--}}
                            {{--</div>--}}
                        </div>
                        {{--<div class="mobile-third-accounts">--}}
                            {{--<a class="button xzl-button-chrome-less xzl-marign-left32  profile-third-account-logo  weibo  button--withIcon button--withSvgIcon" href="http://weibo.com/u/2477183313" target="_blank">--}}
                                {{--<span class="xzl-default-state-btn">--}}
                                    {{--<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="22px" height="19px" viewBox="0 0 22 19" version="1.1"><!-- Generator: Sketch 44.1 (41455) - http://www.bohemiancoding.com/sketch --><title></title><desc>Created with Sketch.</desc>--}}
                                        {{--<defs></defs><g id="切图" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g transform="translate(-204.000000, -229.000000)" id="icon_mine_weibo" fill="#818181"><g transform="translate(204.000000, 229.000000)"><path d="M8.682,19.0015 C4.414,19.0015 0,16.813 0,13.1495 C0,11.311 1.0825,9.18 3.0485,7.1505 C4.9335,5.2065 7.08,3.9985 8.6505,3.9985 C9.277,3.9985 9.8005,4.1975 10.1655,4.5745 C10.5325,4.9525 10.884,5.654 10.6315,6.9015 C11.564,6.536 12.4405,6.3435 13.185,6.3435 C14.3855,6.3435 14.9715,6.8355 15.2505,7.2475 C15.6545,7.8425 15.6855,8.628 15.345,9.5845 C17.019,10.137 18.014,11.3335 18.014,12.796 C18.014,15.731 14.1815,19.0015 8.682,19.0015 L8.682,19.0015 Z M8.6505,4.9985 C7.3805,4.9985 5.418,6.143 3.767,7.8465 C1.9825,9.688 1,11.5715 1,13.1495 C1,15.535 3.8735,18.0015 8.682,18.0015 C13.844,18.0015 17.014,14.97 17.014,12.796 C17.014,11.327 15.6035,10.7175 14.9975,10.523 C14.788,10.4585 14.4975,10.368 14.3525,10.0685 C14.2055,9.764 14.3255,9.4535 14.365,9.3515 C14.547,8.8775 14.7055,8.225 14.423,7.809 C14.1625,7.424 13.6055,7.3435 13.185,7.3435 C12.485,7.3435 11.6205,7.5575 10.6845,7.9615 C10.681,7.963 10.677,7.9645 10.6735,7.966 C10.548,8.019 10.3615,8.08 10.174,8.08 C9.8615,8.08 9.694,7.916 9.622,7.8175 C9.528,7.69 9.4385,7.466 9.5445,7.1255 C9.8005,6.276 9.7655,5.5985 9.4475,5.2705 C9.2725,5.0895 9.0045,4.9985 8.6505,4.9985 L8.6505,4.9985 Z M21.51,6 C21.234,6 21.01,5.7765 21.01,5.5 C21.01,3.0185 18.9915,1 16.51,1 C16.234,1 16.01,0.7765 16.01,0.5 C16.01,0.2235 16.234,0 16.51,0 C19.543,0 22.01,2.4675 22.01,5.5 C22.01,5.7765 21.7865,6 21.51,6 L21.51,6 Z M18.01,6 C17.734,6 17.51,5.7765 17.51,5.5 C17.51,4.949 17.0615,4.5 16.51,4.5 C16.234,4.5 16.01,4.2765 16.01,4 C16.01,3.7235 16.234,3.5 16.51,3.5 C17.613,3.5 18.51,4.397 18.51,5.5 C18.51,5.7765 18.2865,6 18.01,6 L18.01,6 Z" id="Shape-Copy-3"></path></g></g></g>--}}
                                    {{--</svg>--}}
                                {{--</span>--}}
                            {{--</a>--}}
                        {{--</div>--}}
                        <div class="xzl-button-set xzl-profile-button-set xzl-profile-button-set-setting  u-inlineBlock">
                            <span class="followState js-followState xzl-button-set-inner">
                                <button class="btn btn-default profile-setting-btn  edit-profile-button">
                                    <span class="button-label  xzl-default-state-btn js-buttonLabel">设置</span>
                                </button>
                                <button class="btn btn-default edit-profile-save-button  hidden" data-action="save-profile" data-url="/users/29047" data-profile-url="/u/5775582212">
                                    <span class="button-label   js-buttonLabel">保存</span>
                                </button>
                                <button class="btn btn-default edit-profile-cancel-button  hidden" data-action="cancel-edit">
                                    <span class="button-label js-buttonLabel">取消</span>
                                </button>
                            </span>
                        </div>
                    </div>
                </header>
                <div class="home-container">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="{{ url('/') }}" >文章</a>
                        </li>
                        <li role="presentation">
                            <a href="{{ url('/subscribes') }}" >订阅</a>
                        </li>
                        <li role="presentation">
                            <a href="{{ url('/comments') }}" >回复</a>
                        </li>
                        <li role="presentation">
                            <a href="{{ url('/favorites') }}" >喜欢</a>
                        </li>
                        <li role="presentation">
                            <a href="{{ url('/collects') }}" >收藏</a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="poem">
                            <div class="pane-content">
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
                                <div class="write-topics">

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