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
            font-size: 32px;
            color: #2D2D2F;
            line-height: 45px;
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
        span.time{
            font-size: 14px;
            color: #C7C7C7;
            display: inline-block;
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
        @media(max-width: 768px){
            .content{
                padding-left: 0;
                padding-right: 0;
            }
        }
    </style>
@endsection

@section('content')
    <main class="main_content col-md-12 no-padding">
        <div class="content col-md-9 col-md-offset-1 col-xs-12 zhuanlan-new">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="container-stream col-md-9  col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('/')}}">文章</a></li>
                    <li class="active">{{@$post->title}}</li>
                </ol>
                <div class="xzl-aspect-ratio-placeholder-fill">
                    <img class="topic-show" src="{{asset(@$post->cover_url)}}" alt="{{@$post->title}}">
                </div>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="topic-body">
                    {!! @$post->content !!}
                </div>
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
            </div>
        </div>
    </main>
@endsection

@section('content-js')

@endsection