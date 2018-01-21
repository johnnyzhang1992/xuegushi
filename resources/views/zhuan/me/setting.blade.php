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
        ul{
            padding-left: 0;
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
        .me-setting-body .item .item-value .tip {
            font-size: 14px;
            color: #818181;
            line-height: 23px;
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
        .me-setting-body .basic-items, .xzl-main-container .me-setting-body .third-account-items {
            border-bottom: 1px solid #EDEDED;
            padding-bottom: 70px;
        }
        .me-setting-body .item {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            line-height: 23px;
            margin-top: 40px;
        }
        .me-setting-body .item label.notop {
            margin-top: 0px;
        }
        .me-setting-body .item label,.me-setting-body .item .item-show {
            height: 36px;
            line-height: 36px;
            font-size: 16px;
            color: #2D2D2F;
            line-height: 23px;
            min-width: 199px;
            margin-top: 6.5px;
            margin-bottom: 0px;
            font-weight: initial;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }
        .me-setting-body .items .item-value {
            width: 100%;
            height: 100%;
        }
        .me-setting-body .setting-edit-btn {
            font-size: 16px;
            color: #FF7055;
            line-height: 23px;
            position: absolute;
            right: 0;
        }
        .me-setting-body .items .item-value .edit-item {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }
        .me-setting-body .items .item-value .edit-item input, .me-setting-body .items .item-value .edit-item textarea {
            width: 100%;
            max-width: 331px;
            font-size: 16px;
            color: #2D2D2F;
        }
        .me-setting-body .item .item-value input {
            border: 1px solid #EDEDED;
            border-radius: 4px;
            height: 36px;
            padding-left: 10px;
            font-size: 16px;
            color: #2D2D2F;
            line-height: 23px;
        }
        .me-setting-body .items .item-value button.save-button {
            min-width: 60px;
            width: 60px;
            height: 32px;
            margin-left: 10px;
            line-height: 32px;
        }
        .me-setting-body .items .item-value .button {
            height: 36px;
            line-height: 36px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            min-width: 156px;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
        }
        .xzl-basic-btn {
            font-size: 14px;
            color: #FF7055;
            vertical-align: middle;
            border: 1px solid #FF7055;
            border-radius: 100px;
        }
        .button {
            display: inline-block;
            position: relative;
            height: 37px;
            padding: 0 16px;
            color: rgba(0,0,0,0.44);
            background: transparent;
            font-size: 14px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            vertical-align: bottom;
            white-space: nowrap;
            text-rendering: auto;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            box-sizing: border-box;
            border-radius: 999em;
            font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen,Ubuntu,Cantarell,"Open Sans","Helvetica Neue",sans-serif;
            letter-spacing: 0;
            font-weight: 400;
            font-style: normal;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            -moz-font-feature-settings: "liga" on;
        }
        .button--small {
            height: 32px;
            line-height: 30px;
            padding: 0 14px;
            font-size: 12px;
        }
        .me-setting-body .items .item-value button.cancel-btn {
            min-width: 60px;
            width: 60px;
            height: 32px;
            margin-left: 10px;
            line-height: 32px;
        }
        .me-setting-body .items .item-value button span {
            font-size: 14px;
        }
        .xzl-base-gray-btn.xzl-base-color-normal-btn {
            border: 1px solid #EDEDED;
            border-radius: 100px;
        }
        .me-setting-body .item .item-value input.user-indentity_id {
            width: 112px;
        }
        .me-setting-body .items .item-value .with-prefix {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }
        .me-setting-body .item .item-value .prefix {
            font-size: 16px;
            color: #2D2D2F;
            line-height: 23px;
        }
        .me-setting-body .items .item-value .tip .error {
            color: #FF7055;
            float: right;
        }
        .avatar {
            display: block;
            white-space: nowrap;
            overflow: visible;
            text-overflow: ellipsis;
            line-height: normal;
            position: relative;
        }
        .user-avatar-edit .u-relative {
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
        .u-relative {
            position: relative !important;
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
        .profile-avatar .edit-avatar-mask {
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
        .me-setting-body .items .item-value .edit-item textarea {
            width: 100%;
            max-width: 331px;
            font-size: 16px;
            color: #2D2D2F;
        }
        .me-setting-body .third-account-items {
            border-bottom: 1px solid #EDEDED;
            padding-bottom: 70px;
        }
        .me-setting-body .items div.tip {
            font-size: 14px;
            color: #818181;
            line-height: 23px;
            margin-top: 25px;
        }
        .me-setting-body .items .item-value .zhihu-binded {
            height: 36px;
            line-height: 36px;
        }
        .me-setting-body .items .item-value .zhihu-item-edit {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }
        .me-setting-body .items .item-value .zhihu-item-edit input {
            width: 100%;
        }
        .me-setting-body .items .item-value .zhihu-item-edit button.button {
            min-width: 60px;
            width: 60px;
            height: 32px;
            margin-left: 10px;
            line-height: 32px;
        }
        .me-setting-body .items .item-value .zhihu-item-edit button.button {
            min-width: 60px;
            width: 60px;
            height: 32px;
            margin-left: 10px;
            line-height: 32px;
        }
        .xzl-button-set {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }
        button .flex-btn {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            width: inherit;
            margin: auto;
        }
        .me-setting-body .items .item-value .button .third-logo {
            margin-right: 6px;
            vertical-align: initial;
        }
        .xzl-button-set .xzl-blue-color-normal-btn, .xzl-button-set .xzl-blue-color-normal-btn:hover, .xzl-button-set .xzl-blue-color-normal-btn:visited, .xzl-button-set .xzl-blue-color-normal-btn:active, .xzl-button-set .xzl-blue-color-normal-btn:focus {
            background: #3288EB;
            border-radius: 100px;
            border: none;
            outline: none;
        }
        .xzl-button-set .xzl-blue-color-normal-btn span, .xzl-button-set .xzl-blue-color-normal-btn:hover span, .xzl-button-set .xzl-blue-color-normal-btn:visited span, .xzl-button-set .xzl-blue-color-normal-btn:active span, .xzl-button-set .xzl-blue-color-normal-btn:focus span {
            font-size: 16px;
            color: #FFFFFF;
        }
        .xzl-button-set .xzl-ligth-red-color-normal-btn{
            background: #FF5D5D;
            border-radius: 100px;
            border: none;
            outline: none;
        }
       .button-label.xzl-default-state-btn.js-buttonLabel{
           font-size: 16px;
           color: #FFFFFF;
       }
        .me-setting-body .items .item-value input.disabled {
            background: #F5F5F5;
            border: 1px solid #EDEDED;
            border-radius: 4px;
        }
        @media (max-width: 730px){
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
            <div class="container-stream col-md-9 col-md-offset-2 col-xs-12">
                <div class="home-container">
                    <div class="js-profileHeaderBlock">
                        <div class="zhuanlan-list-header">
                            <span class="tip">个人设置</span>
                        </div>
                    </div>
                    <div class="me-setting-body">
                        <div class="basic-items items">
                            <div class="item item-center">
                                <label class="notop">用户名</label>
                                <div class="item-value ">
                                    <div class="item-value-partial item-show name_show">
                                        <span class="name">{{@$me->name}}</span>
                                        <a class="setting-edit-btn edit-btn setting-name-edit-btn hidden" href="javascript:void(0)">编辑</a>
                                    </div>
                                    <div class="name-item-edit edit-item name-item-edit hidden">
                                        <input class="user-name-item  edit-item " value="{{@$me->name}}">
                                        <button class="button button--small xzl-base-color-normal-btn save-button name-btn user-name-save-btn xzl-basic-btn xzl-btn-with-chrome xzl-base-color-normal-btn">
                                            <span class="button-label  xzl-default-state-btn ">确定</span>
                                        </button>
                                        <button class="button button--small user-name-cancel-btn cancel-btn name-btn xzl-base-gray-btn xzl-basic-btn xzl-btn-with-chrome xzl-base-color-normal-btn">
                                            <span class="button-label xzl-default-state-btn ">取消</span>
                                        </button>
                                    </div>
                                    <span class="tip">唯一值，不可和其它用户重名<span class="error name-error hidden">错误提示</span></span>
                                </div>
                            </div>
                            <div class="item">
                                <label>个人域名</label>
                                <div class="item-value">
                                    <div class="with-prefix">
                                        <span class="prefix">https://zhuanlan.xuegushi.cn/people/</span>
                                        <input type="text" name="indentity_id" class="user-indentity_id" data-required="true" data-min-length="2" data-max-length="50" data-required-error="" value="{{@$me->domain}}" @if(isset($me->domain) && mb_strlen($me->domain)>0)  disabled @endif>
                                        @if(!isset($me->domain) || mb_strlen($me->domain)<1)
                                            <button class="button button--small  disabled xzl-base-color-normal-btn  hidden save-button user-identity-id-save-btn xzl-basic-btn xzl-btn-with-chrome xzl-base-color-normal-btn">
                                                <span class="button-label  xzl-default-state-btn ">确定</span>
                                            </button>
                                            <button class="button button--small cancel-btn  user-identity-id-cancel-btn  hidden xzl-base-gray-btn xzl-basic-btn xzl-btn-with-chrome xzl-base-color-normal-btn">
                                                <span class="button-label xzl-default-state-btn ">取消</span>
                                            </button>
                                        @endif
                                    </div>
                                    <span class="tip">仅可修改一次<span class="error  indentity_id-error hidden">错误提示</span></span>
                                </div>
                            </div>
                            <div class="item item-center">
                                <label class="notop">电子邮箱</label>
                                <div class="item-value ">
                                    <div class="item-value-partial item-show email_show">
                                        <span class="email">{{@$me->email}}</span>
                                        <a class="setting-edit-btn edit-btn hidden setting-email-edit-btn" href="javascript:void(0)">编辑</a>
                                    </div>
                                    <div class="email-item-edit edit-item email-item-edit  hidden">
                                        <input type="text" class="user-email-item" value="{{@$me->email}}">
                                        <button class="button button--small xzl-base-color-normal-btn save-button email-btn email-save-btn xzl-basic-btn xzl-btn-with-chrome xzl-base-color-normal-btn hidden">
                                            <span class="button-label  xzl-default-state-btn ">
                                                确定
                                            </span>
                                        </button>
                                        <button class="button button--small cancel-btn email-btn xzl-base-gray-btn xzl-basic-btn xzl-btn-with-chrome xzl-base-color-normal-btn hidden">
                                            <span class="button-label xzl-default-state-btn ">取消</span>
                                        </button>
                                    </div>
                                    <span class="tip">此邮箱为登录邮箱，请谨慎修改<span class="error email-error hidden">错误提示</span></span>
                                </div>
                            </div>
                            <div class="item item-center">
                                <label class="notop">个人头像</label>
                                <div class="user-avatar-dropzone dz-clickable"><div class="avatar profile-avatar user-avatar-edit profile-avatar-edit">
                                        <div class="u-relative">
                                            <img class="avatar-image .xzl-size-80x80 xzl-width-80 xzl-height-80 js-profileAvatarImage" src="{{ asset(@$me->avatar) }}" alt="7d167b1026d6cb3353d86ebb38df4f69">
                                            <form action="{{ url('uploads/userAvatar') }}" id="fm_dropzone_main" enctype="multipart/form-data" method="POST">
                                                {{ csrf_field() }}
                                                <div class="dz-message edit-avatar-mask">
                                                    <div class=" zhuanlan-logo-dropzone dz-clickable">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="27px" viewBox="0 0 30 27" version="1.1" class="edit-image-div">
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
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item item-center">
                                <label class="notop">个人简介</label>
                                <div class="item-value ">
                                    <div class="item-value-partial item-show bio_show ">
                                        <span class="bio">时间会告诉你答案。</span>
                                        <a class="setting-edit-btn edit-btn setting-bio-edit-btn hidden" href="javascript:void(0)">编辑</a>
                                    </div>
                                    <div class="bio-item-edit edit-item bio-item-edit hidden">
                                        <textarea type="text" id="user-bio-item" class="user-bio-item">时间会告诉你答案。</textarea>
                                        <button class="button button--small xzl-base-color-normal-btn hidden save-button bio-btn bio-save-btn xzl-basic-btn xzl-btn-with-chrome xzl-base-color-normal-btn">
                                            <span class="button-label  xzl-default-state-btn ">确定</span>
                                        </button>
                                        <button class="button button--small cancel-btn bio-btn hidden  xzl-base-gray-btn xzl-basic-btn xzl-btn-with-chrome xzl-base-color-normal-btn">
                                            <span class="button-label xzl-default-state-btn ">取消</span>
                                        </button>
                                    </div>
                                    <span class="tip item-show-tip">关于个人介绍会显示在您的个人主页上<span class="error  bio-error hidden">错误提示</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="third-account-items items hidden">
                            <div class="tip">绑定社交账号，在你的个人主页将以图标形式展示</div>
                            <div class="item">
                                <label>绑定微博</label>
                                <div class="item-value">
                                    <div class="item-value-partial weibo-binded hidden">
                                        <a target="_blank" href="http://weibo.com/u/2477183313">
                                            <span class="third-account-name">小小梦工场</span>
                                        </a>
                                    </div>
                                    <div class="weibo-item weibo-unbinded xzl-button-set">
                                        <button class="button disabled xzl-ligth-red-color-normal-btn link-btn" data-link-url="/account/auth/weibo">
                                            <div class="flex-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="20px" viewBox="0 0 24 20" version="1.1" class="weibo-logo third-logo">
                                                    <!-- Generator: Sketch 44.1 (41455) - http://www.bohemiancoding.com/sketch -->
                                                    <title></title>
                                                    <desc>Created with Sketch.</desc>
                                                    <defs></defs>
                                                    <g id="切图" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <g transform="translate(-154.000000, -517.000000)" id="icon_setting_weibo" fill-rule="nonzero" fill="#FFFFFF">
                                                            <g transform="translate(154.000000, 517.000000)">
                                                                <path d="M22.061496,7.17947337 C21.4950993,7.17947337 21.035857,6.72054818 21.035857,6.15383432 C21.035857,3.89193341 19.1952017,2.05127811 16.9333008,2.05127811 C16.3669041,2.05127811 15.9076617,1.59235292 15.9076617,1.02563905 C15.9076617,0.458925191 16.3669041,0 16.9333008,0 C20.3271166,0 23.0871351,2.76019168 23.0871351,6.15383432 C23.0871351,6.72064592 22.6283076,7.17947337 22.061496,7.17947337 Z M18.4717594,7.17947337 C17.9053626,7.17947337 17.4461203,6.72054818 17.4461203,6.15383432 C17.4461203,5.87171298 17.2155017,5.64101479 16.9333008,5.64101479 C16.3669041,5.64101479 15.9076617,5.1820896 15.9076617,4.61537574 C15.9076617,4.04866188 16.3669041,3.58973669 16.9333008,3.58973669 C18.3478031,3.58973669 19.4973984,4.73933204 19.4973984,6.15383432 C19.4973984,6.72064592 19.038571,7.17947337 18.4717594,7.17947337 Z M8.90459826,20.0015 C4.52717078,20.0015 0,17.7568889 0,13.9994603 C0,12.1138229 1.11025428,9.92818604 3.12666065,7.84665158 C5.05999027,5.85280926 7.2615245,4.61383728 8.87229063,4.61383728 C9.5148535,4.61383728 10.0517755,4.81793945 10.4261338,5.20460538 C10.8025433,5.59229694 11.1630555,6.31178273 10.9040816,7.59126745 C11.86049,7.21639638 12.7594626,7.01896086 13.5230509,7.01896086 C14.7543306,7.01896086 15.3553551,7.52357528 15.6415084,7.94613857 C16.0558666,8.5563938 16.0876614,9.36203328 15.7384313,10.343057 C17.455351,10.9097226 18.4758619,12.1368997 18.4758619,13.6368969 C18.4758619,16.6471475 14.5451002,20.0015 8.90459826,20.0015 Z" id="Combined-Shape"></path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                                <span class="button-label  xzl-default-state-btn js-buttonLabel">关联微博</span>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <label>知乎主页</label>
                                <div class="item-value">
                                    <div class="item-value-partial item-show zhihu-binded  hidden">
                                        <span class="third-account-name"><a target="blank" href="https://www.zhihu.com/people/johnnyzhang1992">https://www.zhihu.com/people/johnnyzhang1992</a></span>
                                        <a class="setting-edit-btn edit-btn hidden setting-zhihu-edit-btn" href="javascript:void(0)">编辑</a>
                                    </div>
                                    <div class="zhihu-item-edit hidden">
                                        <input type="text" name="zhihu_url" class="user-zhihu-link" value="https://www.zhihu.com/people/johnnyzhang1992">
                                        <button class="button button--small xzl-base-color-normal-btn zhihu-save-btn xzl-basic-btn xzl-btn-with-chrome xzl-base-color-normal-btn">
                                            <span class="button-label  xzl-default-state-btn ">确定</span>
                                        </button>
                                        <button class="button button--small cancel-btn  xzl-base-gray-btn xzl-basic-btn xzl-btn-with-chrome xzl-base-color-normal-btn">
                                            <span class="button-label xzl-default-state-btn ">取消</span>
                                        </button>
                                    </div>
                                    <div class="zhihu-item zhihu-unbinded xzl-button-set">
                                        <button class="button disabled xzl-blue-color-normal-btn setting-zhihu-btn">
                                            <div class="flex-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="19px" viewBox="0 0 20 19" version="1.1" class="zhihu-logo third-logo">
                                                    <!-- Generator: Sketch 44.1 (41455) - http://www.bohemiancoding.com/sketch -->
                                                    <title></title>
                                                    <desc>Created with Sketch.</desc>
                                                    <defs></defs>
                                                    <g id="切图" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <g transform="translate(-216.000000, -518.000000)" id="icon_setting_zhihu" fill="#FFFFFF">
                                                            <g transform="translate(216.000000, 518.000000)">
                                                                <path d="M4.62573287,-3.12638804e-13 L3.7093141,2.59616304 C3.7093141,2.59616304 8.90235356,2.59616304 9.60057757,2.59616304 C10.2988016,2.59616304 10.3424405,4.17088488 10.3424405,4.17088488 L6.72040437,4.17088479 L6.72040437,8.93761023 C6.72040437,8.93761023 9.16418784,8.8524899 9.94968974,8.93761023 C10.7351916,9.02273055 10.7351914,10.5548923 10.7351914,10.5548923 L6.72040437,10.5548921 L6.05108301,13.1575266 L6.99160129,12.3488853 C6.99160129,12.3488853 9.14489264,14.7265312 9.54090003,15.3179256 C9.93690742,15.90932 9.60057766,18.0029011 9.60057766,18.0029011 L5.91495562,13.6040898 C5.91495562,13.6040898 4.75664976,17.53474 3.18564622,18.4285016 C1.61464268,19.322263 0,18.6838617 0,18.6838617 C0,18.6838617 2.22558848,17.1517005 3.40384112,15.1939379 C4.58209379,13.2361754 4.97484487,10.5548921 4.97484487,10.5548921 L0.392750895,10.5548921 C0.392750895,10.5548921 0.741862744,8.98017051 1.35280864,8.93761023 C1.96375454,8.89504995 4.97484479,8.93761046 4.97484479,8.93761046 L4.9312058,4.08576478 L3.1856461,4.17088479 C3.1856461,4.17088479 2.8801732,5.3625664 1.92011558,6.04352703 C0.960057998,6.72448784 0.392750895,6.46912767 0.392750895,6.46912767 C0.392750895,6.46912767 2.09467161,1.87264218 2.61833941,0.978881135 C3.14200719,0.0851200517 4.62573287,-3.12638804e-13 4.62573287,-3.12638804e-13 Z M11.5922162,2.44578996 L19.85,2.44578995 L19.85,16.7763335 L16.2004442,16.7763335 L13.2615334,18.865877 L13.2615334,16.9981317 L11.3679418,16.9981317 L11.5922162,2.44578996 Z M13.3405912,4.04074107 L13.3405912,15.1486901 L13.7910996,15.1486901 L14.2680277,16.757036 L16.4554923,15.1486901 L18.1716329,15.1486901 L18.1716329,4.04074107 L13.3405912,4.04074107 Z" id="zhihu_L"></path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                                <span class="button-label  xzl-default-state-btn js-buttonLabel">添加知乎</span>
                                            </div>
                                        </button>
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
    <script src="{{ asset('la-assets/plugins/dropzone/dropzone.js') }}"></script>
    <script>
        $(".item-show").hover(function(){
            $(this).find(".edit-btn").removeClass("hidden")
        });

        $(".item-show").mouseleave(function(){
            $(this).find(".edit-btn").addClass("hidden")
        });
        function validateEmail(e) {
            return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(e)
        }
        // upload
        var bsurl = '{{ url('') }}';
        var fm_dropzone_main = null;
        var cntFiles = null;
        $(function () {
            fm_dropzone_main = new Dropzone("#fm_dropzone_main", {
                maxFilesize: 2,
                acceptedFiles: "image/*",
                init: function() {
                    this.on("complete", function(file) {
                        this.removeFile(file);
                    });
                    this.on("success", function(file) {
                        console.log("addedfile");
                        var res = JSON.parse(file.xhr.response);
                        console.log(JSON.parse(file.xhr.response));
                        $('.zhuanlan-logo svg').hide();
                        $('.js-profileAvatarImage').attr('src',res.url);
                    });
                }
            });
        });
    </script>
    {{--name--}}
    <script type="text/javascript">
        var showname = function(){
            var text = $(".name_show span").text();
            $(".name_show").removeClass("hidden");
            $(".name-item-edit").addClass("hidden");
        };
        $(".setting-name-edit-btn").click(function(){
            $(".name_show").addClass("hidden");
            $(".name-item-edit").removeClass("hidden");
        });

        $(".user-name-item").focus(function(){
            $(".name-btn").removeClass("hidden");
            $(".name-error").addClass("hidden")
        });

        $(".user-name-save-btn").click(function(){
            var name = $(".user-name-item").val();
            if(!name){
                $(".name-error").text("用户名不能为空");
                $(".name-error").removeClass("hidden");
                return
            }
            $.ajax({
                dataType: 'json',
                data:{
                    'name':'name' ,
                    'val':name,
                    _token: $('input[name="_token"]').val()
                },
                type:'POST',
                url: "/me/update",
                success: function (json) {
                    // console.log(json);
                    if(json && json.status){
                        $(".user-name-item").addClass("disabled");
                        $(".user-name-item").attr("disabled", "disabled");
                        $(".user-name-save-btn").addClass("hidden");
                        $(".user-name-cancel-btn").addClass("hidden")
                    }else{
                        $('.name-error').text(json.msg);
                        $(".name-error").removeClass("hidden");
                    }
                }
            });
        });
        $(".name-binded").click(function(){
            $(".name_show").addClass("hidden");
            $(".name_show").removeClass("hidden");
            // $(".user-name-link").putCursorAtEnd()
        });

        $(".name-item-edit .user-name-cancel-btn").click(function(){
            showname()
        })
    </script>
    {{--个性域名--}}
    <script type="text/javascript">

        $('.user-indentity_id').focus(function(){
            if($(this).hasClass("disabled")){
                return
            }
            $(".indentity_id-error").addClass("hidden");
            $(".user-identity-id-save-btn").removeClass("hidden");
            $(".user-identity-id-save-btn").removeClass("hidden");
            $(".user-identity-id-cancel-btn").removeClass("hidden")
        });

        $(".user-identity-id-cancel-btn").click(function(){
            $(".user-identity-id-save-btn").addClass("hidden");
            $(".user-identity-id-cancel-btn").addClass("hidden");
            $(".indentity_id-error").addClass("hidden");
        });

        $("input.user-indentity_id").blur(function(){
            var error = null;
            if($(this).data("min-length") && $(this).val().length < $(this).data("min-length")){
                error = $(this).parent().parent().find("label").text().replace("*", "")+ "不能少于" + $(this).data("min-length") + "个文字"
            }
            if($(this).data("max-length") && $(this).val().length > $(this).data("max-length")){
                error = $(this).parent().parent().find("label").text().replace("*", "")+ "不能超过" + $(this).data("max-length") + "个文字"
            }

            if($(this).data("required-error") && !$(this).val()){
                error = $(this).data("required-error")
            }

            if($(this).attr("name") == "indentity_id" && $(this).val()){
                if(!!$(this).val() && !$(this).val().match(/\w|\d|_|-|－+/g)){
                    error = "个人域名只能是字母、数字、_、-"
                }else{
                    if($(this).val().match(/\w|\d|_|－|-+/g).join("") != $(this).val()){
                        error = "个人域名只能是字母、数字、_、-"
                    }
                }
            }
            if(!!error){
                $(this).parent().parent().find("span.error").text(error);
                $(this).parent().parent().find("span.error").removeClass("hidden");
                $(".user-identity-id-save-btn").addClass("disabled")
            }else{
                $(".user-identity-id-save-btn").removeClass("disabled");
                $(this).parent().find("span.error").text(error);
                $(this).parent().find("span.error").removeClass("hidden")
            }

        });


        $(".user-identity-id-save-btn").click(function(){
            if($(".user-identity-id-save-btn").hasClass("disabled")){
                return
            }
            $.ajax({
                dataType: 'json',
                data:{
                    'name':'domain' ,
                    'val':$(".user-indentity_id").val(),
                    _token: $('input[name="_token"]').val()
                },
                type:'POST',
                url: "/me/update",
                success: function (json) {
                    // console.log(json);
                    if(json && json.status){
                        $(".user-indentity_id").addClass("disabled")
                        $(".user-indentity_id").attr("disabled", "disabled")
                        $(".user-identity-id-save-btn").addClass("hidden")
                        $(".user-identity-id-cancel-btn").addClass("hidden")
                    }else{
                        $('.indentity_id-error').text(json.msg);
                        $(".indentity_id-error").removeClass("hidden");
                    }
                }
            });
        })
    </script>
    {{--Email--}}
    <script type="text/javascript">
        var showemail = function(){
            var text = $(".email_show span").text();
            if(text){
                $(".email_show").removeClass("hidden");
                $(".email-item-edit").addClass("hidden");
                $(".email-error").addClass("hidden")
            }else{
                $(".user-email-item").removeClass("hidden");
                $(".email-btn").addClass("hidden")
            }
        };
        $(".setting-email-edit-btn").click(function(){
            $(".email_show").addClass("hidden");
            $(".email-item-edit").removeClass("hidden");
        });

        $(".user-email-item").focus(function(){
            $(".email-btn").removeClass("hidden");
            $(".email-error").addClass("hidden")
        });

        $(".email-save-btn").click(function(){
            var email = $(".user-email-item").val()
            if(!!email && validateEmail(email)){
                $(".email-error").text("请填写正确的邮箱");
                $(".email-error").removeClass("hidden");
                return
            }
            $.ajax({
                dataType: 'json',
                data:{
                    'name':'email' ,
                    'val':email,
                    _token: $('input[name="_token"]').val()
                },
                type:'POST',
                url: "/me/update",
                success: function (json) {
                    // console.log(json);
                    if(json && json.status){
                        $(".email-item-edit").addClass("hidden");
                        $(".email_show").removeClass("hidden")
                    }else{
                        $('.email-error').text(json.msg);
                        $(".email-error").removeClass("hidden");
                    }
                }
            });
        });
        $(".email-binded").click(function(){
            $(".email_show").addClass("hidden")
            $(".email_show").removeClass("hidden")
            $(".user-email-link").putCursorAtEnd()
        })

        $(".email-item-edit .cancel-btn").click(function(){
            showemail()
        })
    </script>
    {{--介绍--}}
    <script type="text/javascript">
        var showbio = function(){
            var text = $(".bio_show span").text();
            if(text){
                $(".bio_show").removeClass("hidden");
                $(".bio-item-edit").addClass("hidden");
                $(".bio-error").addClass("hidden")
            }else{
                $(".user-bio-item").removeClass("hidden");
                $(".bio-btn").addClass("hidden")
            }
            $(".item-show-tip").hide()

        };
        $(".setting-bio-edit-btn").click(function(){
            $(".user-bio-item").val($(".user-bio-item").val());
            $(".bio_show").addClass("hidden");
            $(".bio-item-edit").removeClass("hidden");
            //$(".user-zhihu").val($(".user-zhihu").val())
            // $(".user-bio-item").putCursorAtEnd()
            $(".item-show-tip").show();

            // autosize(document.getElementById('user-bio-item'));
            $(".user-bio-item").css({"min-height": "80px"})
        });

        $(".user-bio-item").focus(function(){
            $(".bio-btn").removeClass("hidden");
            $(".bio-error").addClass("hidden")
        });

        $(".bio-save-btn").click(function(){
            var bio = $(".user-bio-item").val();
            $.ajax({
                dataType: 'json',
                data:{
                    'name':'about' ,
                    'val':bio,
                    _token: $('input[name="_token"]').val()
                },
                type:'POST',
                url: "/me/update",
                success: function (json) {
                    // console.log(json);
                    if(json && json.status){
                        $(".bio-item-edit").addClass("hidden")
                        $(".bio_show").removeClass("hidden")
                        $(".bio_show span").html( $(".user-bio-item").val())
                        $(".item-show-tip").hide()
                    }else{
                        $('.bio-error').text(json.msg);
                        $(".bio-error").removeClass("hidden");
                    }
                }
            });
        });
        $(".bio-binded").click(function(){
            $(".bio_show").addClass("hidden");
            $(".bio_show").removeClass("hidden");
            // $(".user-bio-link").putCursorAtEnd()
        });

        $(".bio-item-edit .cancel-btn").click(function(){
            showbio()
        })
    </script>
    {{--知乎--}}
    <script type="text/javascript">
        var showZhihu = function(){
            var text = $(".zhihu-binded span").text()
            if(!text){
                $(".zhihu-binded").addClass("hidden")
                $(".zhihu-item-edit").addClass("hidden")
                $(".zhihu-unbinded").removeClass("hidden")
            }else{
                $(".zhihu-binded").removeClass("hidden")
                $(".zhihu-item-edit").addClass("hidden")
                $(".zhihu-unbinded").addClass("hidden")
            }
        }
        $(".setting-zhihu-btn").click(function(){
            $(".zhihu-item").addClass("hidden")
            $(".zhihu-item-edit").removeClass("hidden")
            //$(".user-zhihu").val($(".user-zhihu").val())
            // $(".user-zhihu-link").putCursorAtEnd()
        })

        $(".zhihu-save-btn").click(function(){
            window.app.fetch("/me/settings", "put", {"zhihu_link": $(".user-zhihu-link").val()}, function(data){
                console.log(data.user.zhihu_link)
                $(".zhihu-binded span").html(`<a href="${data.user.zhihu_link}" target="_blank"> ${data.user.zhihu_link}</a>`)
                showZhihu()
            })

        })
        $(".zhihu-binded .edit-btn").click(function(){
            $(".zhihu-binded").addClass("hidden")
            $(".zhihu-item-edit").removeClass("hidden")
            // $(".user-zhihu-link").putCursorAtEnd()
        })

        $(".zhihu-item-edit .cancel-btn").click(function(){
            showZhihu()
        })


        var showDribble = function(){
            var text = $(".dribble-binded span").text()
            if(!text){
                $(".dribble-binded").addClass("hidden")
                $(".dribble-item-edit").addClass("hidden")
                $(".dribble-unbinded").removeClass("hidden")
            }else{
                $(".dribble-binded").removeClass("hidden")
                $(".dribble-item-edit").addClass("hidden")
                $(".dribble-unbinded").addClass("hidden")
            }
        }
        $(".setting-dribble-btn").click(function(){
            $(".dribble-item").addClass("hidden")
            $(".dribble-item-edit").removeClass("hidden")
            //$(".user-zhihu").val($(".user-zhihu").val())
            // $(".user-dribble-link").putCursorAtEnd()
        })

        $(".dribble-save-btn").click(function(){
            window.app.fetch("/me/settings", "put", {"dribble_link": $(".user-dribble-link").val()}, function(data){
                console.log(data.user.dribble_link)
                $(".dribble-binded span").html(`<a href='${data.user.dribble_link}' target="_blank">${data.user.dribble_link}</a>`)
                showDribble()
            })

        })
        $(".dribble-binded .edit-btn").click(function(){
            $(".dribble-binded").addClass("hidden")
            $(".dribble-item-edit").removeClass("hidden")
            $(".user-dribble-link").putCursorAtEnd()
        })

        $(".zhihu-item-edit .cancel-btn").click(function(){
            showZhihu()
        })

        $(".dribble-item-edit .cancel-btn").click(function(){
            showDribble()
        })
    </script>
@endsection