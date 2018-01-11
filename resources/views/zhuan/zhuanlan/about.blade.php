<?php
use App\Helpers\DateUtil;
?>
@extends('zhuan.layout.zhuanlan')

@section('content-css')
    <style>
        .Navbar-titleContent {
            padding: 10px 15px;
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
            color: #333;
            overflow: hidden;
            display: inline-flex;
            height: 60px;
        }
        .Navbar a {
            line-height: 38px;
            -webkit-transition: color .2s;
            transition: color .2s;
        }
        .container-stream a{
            color: inherit;
            text-decoration: none;
        }
        .Navbar-titleContent img {
            margin-right: 12px;
            border-radius: 50%;
            vertical-align: middle;
        }
        .Navbar-titleContent .Navbar-titleName {
            font-weight: 600;
            font-synthesis: style;
            max-width: calc(100% - 56px);
            display: block;
            line-height: 38px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .Navbar-titleContent .button-follow {
            margin-left: 32px;
        }
        .button-follow{
            height: 38px;
            padding: 5px 15px;
            font-size: 16px;
            line-height: 25px;
        }
        .container-stream{
            padding-left: 0;
            padding-right: 0;
        }
        .Layout-main {
            margin: 47px auto 0;
            padding: 0;
            z-index: 1;
        }
        .AboutIndex-Wrapper h2 {
            font-size: 18px;
            font-weight: 600;
            font-synthesis: style;
            margin: 0 0 32px;
        }
        .AboutIndex-Wrapper .AboutIndex-introContent {
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
            display: flex;
        }
        .AboutIndex-Wrapper .AboutIndex-introContent .AboutIndex-introInfo {
            margin-left: 16px;
            font-size: 15px;
        }
        .AboutIndex-Wrapper .avatar {
            border-radius: 50%;
        }
        .AboutIndex-Wrapper .AboutIndex-introContent .AboutIndex-introInfo h4 {
            font-size: 20px;
            font-weight: 600;
            font-synthesis: style;
            line-height: 22px;
            margin: 16px 0;
        }
        .AboutIndex-Wrapper .AboutIndex-AuthorsWrapper, .AboutIndex-Wrapper .AboutIndex-descriptionWrapper, .AboutIndex-Wrapper .AboutIndex-topicWrapper {
            margin-top: 48px;
        }
        .AboutIndex-Wrapper .block-title {
            line-height: 22px;
            font-weight: 600;
            font-synthesis: style;
            margin: 0 0 32px;
            font-size: 16px;
            position: relative;
        }
        .AboutIndex-Wrapper .block-title span {
            background: #fff;
            position: relative;
            z-index: 1;
            padding-right: 16px;
        }
        .AboutIndex-Wrapper .block-title:after {
            content: "";
            position: absolute;
            height: 1px;
            left: 0;
            right: 0;
            top: 11px;
            background: #f0f0f0;
        }
        .AboutIndex-Wrapper .AboutIndex-topicWrapper .AboutIndex-topicList {
            display: block;
        }
        .AboutIndex-Wrapper .AboutIndex-topicWrapper .AboutIndex-topicList span {
            margin: 0 16px 16px 0;
            display: inline-block;
            color: gray;
            border-radius: 15px;
            font-size: 14px;
            line-height: 20px;
            padding: 5px 16px;
            max-width: 100%;
            background: #eee;
        }
        .AboutIndex-Wrapper .AboutIndex-descriptionWrapper p {
            font-size: 15px;
            line-height: 24px;
        }
        .HelpMenu-help, .HelpMenu-icon {
            display: inline-block;
            background: #fff;
        }
        .HelpMenu-help {
            width: 18px;
            height: 18px;
            z-index: 1;
            position: relative;
            text-align: center;
            line-height: 18px;
            color: gray;
        }
        .HelpMenu-icon {
            cursor: pointer;
            margin-left: -24px;
        }
        .Icon {
            vertical-align: text-bottom;
            fill: #9fadc7;
        }
        .AboutIndex-Wrapper .block-title .HelpMenu-icon {
            margin-left: 2px;
            vertical-align: -2px;
        }
        .AboutIndex-Wrapper .block-title .HelpMenu-menu {
            width: 200px;
            -webkit-transform: translateX(-50%);
            transform: translateX(-50%);
            margin-left: 0;
        }
        .HelpMenu-menu {
            width: 370px;
            margin-left: -198px;
            min-height: auto!important;
            padding: 16px;
            right: auto;
            left: 50%;
            margin-top: 14px;
            font-size: 14px;
            color: gray;
            font-weight: 400;
            cursor: default;
            top: 100%;
            z-index: 40;
            outline: 0;
            position: absolute;
            border: 1px solid rgba(0,0,0,.08);
            border-radius: 4px;
            -webkit-box-shadow: 0 8px 18px rgba(0,0,0,.05);
            box-shadow: 0 8px 18px rgba(0,0,0,.05);
            background-color: #fff;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }
        .HelpMenu-menu:before {
            top: -10px;
            border-bottom: 9px solid rgba(0,0,0,.08);
        }
        .HelpMenu-menu:after {
            top: -9px;
            border-bottom: 9px solid #fff;
        }
        .HelpMenu-menu:after, .HelpMenu-menu:before {
            content: "";
            position: absolute;
            left: 50%;
            width: 0;
            height: 0;
            margin: 0 0 0 -8px;
            font-size: 0;
            border-left: 9px solid transparent;
            border-right: 9px solid transparent;
        }
        ul{
            padding-left: 0;
        }
        .Users-List li {
            position: relative;
            padding-left: 64px;
            min-height: 48px;
            margin-bottom: 40px;
        }
        .Users-List li .Users-Avatar {
            position: absolute;
            top: 0;
            left: 0;
        }
        .Users-List li .Users-Intro {
            line-height: 28px;
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
            font-size: 16px;
            color: gray;
        }
        .Users-List li .Users-Intro a {
            color: #333;
            margin-right: 5px;
            font-weight: 600;
            font-synthesis: style;
        }
        .Users-List li .Users-postsCount {
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
            color: gray;
            margin-top: 3px;
            font-size: 14px;
        }
    </style>
@endsection

@section('top-menu')
    <div class="Navbar-titleContent Navbar">
        <a class="Navbar-titleAvatar" href="{{url(@$zhuan->name)}}">
            <img src="{{ asset(@$zhuan->avatar)}}" class="Navbar-avatar" alt="" width="38"></a>
        <a class="Navbar-titleName" href="{{url(@$zhuan->name)}}">{{@$zhuan->alia_name}}</a>
        <button class="btn btn-success button-follow" type="button">关注专栏</button>
    </div>
@endsection

@section('content')
    <main class="main_content col-md-12 no-padding">
        <div class="content col-md-9 col-xs-12">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="container-stream col-md-9 col-md-offset-2 col-xs-12">
                <div class="Layout-main av-card AboutIndex-Wrapper">
                    <div class="AboutIndex-introWrapper">
                        <h2>关于</h2>
                        <div class="AboutIndex-introContent">
                            <div class="AboutIndex-introAvatar"><img src="{{ asset(@$zhuan->avatar)}}" class="avatar" alt="" width="100"></div>
                            <div class="AboutIndex-introInfo">
                                <h4>{{@$zhuan->alia_name}}</h4>
                                <p>{{@$zhuan->about}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="AboutIndex-topicWrapper">
                        <h3 class="block-title"><span>专栏话题</span></h3>
                        <div class="AboutIndex-topicList">
                            @if(isset($zhuan->topic) && $zhuan->topic)
                                @foreach(explode(",",$zhuan->topic) as $tag)
                                    <span>{{$tag}}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="AboutIndex-descriptionWrapper">
                        <h3 class="block-title"><span>专栏介绍</span></h3>
                        <p>{{@$zhuan->about}}</p>
                    </div>
                    <div class="AboutIndex-AuthorsWrapper">
                        <h3 class="block-title">
                            <span>编辑
                                <div class="HelpMenu-help">
                                    <svg viewBox="0 0 16 16" class="Icon HelpMenu-icon Icon--questionMarkLine" width="24" height="16" aria-hidden="true" style="height: 16px; width: 24px;"><title></title><g><path fill-rule="evenodd" d="M8 16c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8zm0-1c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm0-1.945c-.262 0-.487-.09-.677-.275-.19-.184-.283-.412-.283-.685 0-.263.094-.488.283-.677.19-.19.415-.283.677-.283.262 0 .488.092.677.275.19.184.283.413.283.686 0 .273-.094.5-.283.684-.19.183-.415.275-.677.275zm1.227-4.982c-.13.123-.604.47-.674 1.225-.073.415-.302.622-.685.622-.2 0-.368-.068-.504-.203-.137-.136-.205-.337-.205-.604 0-.335 0-.844.54-1.516.164-.185.39-.405.67-.66.244-.224.925-.753.92-1.34-.003-.318-.114-.588-.343-.807-.228-.22-.523-.33-.884-.33-.423 0-.734.112-.934.333-.2.22-.37.547-.508.978-.13.45-.38.68-.744.68-.215 0-.397-.08-.544-.233-.15-.156-.223-.327-.223-.51 0-.38.118-.762.35-1.15.236-.39.578-.71 1.028-.966.45-.255.974-.383 1.573-.383.557 0 1.855.134 2.463 1.19.232.367.348.766.348 1.196 0 .34-.106.975-.67 1.55-.182.187-.51.5-.982.938z"></path></g>
                                    </svg>
                                    <menu class="HelpMenu-menu" style="display: none"><div class="HelpMenu-menuItem">能发布文章并管理专栏。</div></menu>
                                </div>
                            </span>
                        </h3>
                        <ul class="Users-List">
                            <li>
                                <div class="Users-Avatar">
                                    <a href="{{url('/people/'.@$editor->id)}}" target="_blank"><img src="{{asset(@$editor->avatar)}}" class="avatar" alt="" width="50"></a>
                                </div>
                                <div class="Users-Intro">
                                    <a href="{{url('/people/'.@$editor->id)}}" target="_blank">{{@$editor->name}}</a>
                                    <span class="Users-bio">，{{@$editor->about}}</span>
                                </div>
                                <div class="Users-postsCount">
                                    <a href="{{ url(@$zhuan->name.'?author='.$editor->name)}}">{{@$editor->post_count}} 篇文章</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="AboutIndex-AuthorsWrapper">
                        <h3 class="block-title">
                            <span>作者
                                <div class="HelpMenu-help">
                                    <svg viewBox="0 0 16 16" class="Icon HelpMenu-icon Icon--questionMarkLine" width="24" height="16" aria-hidden="true" style="height: 16px; width: 24px;"><title></title><g><path fill-rule="evenodd" d="M8 16c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8zm0-1c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm0-1.945c-.262 0-.487-.09-.677-.275-.19-.184-.283-.412-.283-.685 0-.263.094-.488.283-.677.19-.19.415-.283.677-.283.262 0 .488.092.677.275.19.184.283.413.283.686 0 .273-.094.5-.283.684-.19.183-.415.275-.677.275zm1.227-4.982c-.13.123-.604.47-.674 1.225-.073.415-.302.622-.685.622-.2 0-.368-.068-.504-.203-.137-.136-.205-.337-.205-.604 0-.335 0-.844.54-1.516.164-.185.39-.405.67-.66.244-.224.925-.753.92-1.34-.003-.318-.114-.588-.343-.807-.228-.22-.523-.33-.884-.33-.423 0-.734.112-.934.333-.2.22-.37.547-.508.978-.13.45-.38.68-.744.68-.215 0-.397-.08-.544-.233-.15-.156-.223-.327-.223-.51 0-.38.118-.762.35-1.15.236-.39.578-.71 1.028-.966.45-.255.974-.383 1.573-.383.557 0 1.855.134 2.463 1.19.232.367.348.766.348 1.196 0 .34-.106.975-.67 1.55-.182.187-.51.5-.982.938z"></path></g>
                                    </svg>
                                    <menu class="HelpMenu-menu" style="display: none"><div class="HelpMenu-menuItem">能发布文章</div></menu>
                                </div>
                            </span>
                        </h3>
                        <ul class="Users-List">
                            @if(isset($authors) && $authors)
                                @foreach($authors as $author)
                                    <li>
                                        <div class="Users-Avatar">
                                            <a href="{{url('/people/'.@$author->id)}}" target="_blank"><img src="{{asset(@$author->avatar)}}" class="avatar" alt="" width="50"></a>
                                        </div>
                                        <div class="Users-Intro">
                                            <a href="{{url('/people/'.@$author->id)}}" target="_blank">{{@$author->name}}</a>
                                            <span class="Users-bio">，{{@$author->about}}</span>
                                        </div>
                                        <div class="Users-postsCount">
                                            <a href="{{ url(@$zhuan->name.'?author='.$author->name)}}">{{@$author->post_count}} 篇文章</a>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
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
        $('.HelpMenu-help svg').hover(function () {
            $(this).next().show();
        },function () {
            $(this).next().hide();
        })
    </script>
@endsection