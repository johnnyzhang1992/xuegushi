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
    <style>
        .PostComment {
            padding-bottom: 48px;
        }
        .PostComment-blockTitle, .PostComment-titleTabs {
            position: relative;
            z-index: 3;
        }
        .PostComment .BlockTitle {
            margin-bottom: 32px;
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
        .CommentEditor {
            display: flex;
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
        }
        .CommentEditor-avatar {
            margin-right: 16px;
        }
        .Avatar-hemingway {
            width: 36px;
            height: 36px;
            border-radius: 50%;
        }
        .CommentEditor-input {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }
        .PostComment .PostComment-mainEditor.CommentEditor--opened .CommentEditor-actions {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }
        .CommentEditor-actions {
            display: flex;
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
            margin-top: 20px;
            -webkit-box-pack: end;
            -ms-flex-pack: end;
            justify-content: flex-end;
        }
        .CommentEditor-actions .Button {
            width: 80px;
            height: 34px;
            margin-left: 16px;
        }
        .PostComment .Button--plain,.CommentItem .Button--plain {
            color: gray;
        }
        .PostComment .Button ,.CommentItem .Button{
            border-radius: 4px;
            border-color: #b3b3b3;
            color: gray;
        }
        .PostComment .Button ,.CommentItem .Button{
            display: inline-block;
            padding: 0 16px;
            font-size: 14px;
            line-height: 32px;
            color: #8590a6;
            text-align: center;
            cursor: pointer;
            background: none;
            border: 1px solid;
            border-radius: 3px;
        }
        .PostComment .Button--link,.PostComment .Button--plain ,.CommentItem .Button--plain{
            height: auto;
            padding: 0;
            line-height: inherit;
            background-color: transparent;
            border: none;
            border-radius: 0;
        }
        .PostComment .Button--plain:hover,.CommentItem .Button--plain:hover {
            color: #77839c;
        }
        .PostComment .Button:focus ,.CommentItem .Button:focus{
            outline: 5px auto #f1f2f3;
            box-shadow: none;
        }
        .PostComment .Button--plain:active,.CommentItem .Button--plain:active {
            color: gray;
            background: transparent;
        }
        .Button[disabled] {
            border-color: #b3b3b3;
            color: gray;
        }
        .Button:disabled {
            cursor: default;
            opacity: .5;
        }
        .PostCommentList {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-box-align: stretch;
            -ms-flex-align: stretch;
            align-items: stretch;
            -ms-flex-negative: 0;
            flex-shrink: 0;
            margin-top: 32px;
        }
        .PostCommentList .CommentItem:first-child {
            margin-top: 0;
        }
        .CommentItem {
            position: relative;
            padding-left: 60px;
            margin-top: 32px;
        }
        .UserAvatar, .UserAvatar-inner {
            vertical-align: top;
        }
        .UserAvatar {
            display: inline-block;
            overflow: hidden;
            border: 4px solid #fff;
            border-radius: 8px;
        }
        .CommentItem-author {
            position: absolute;
            left: 0;
        }
        .Avatar-hemingway {
            width: 36px;
            height: 36px;
            border-radius: 50%;
        }
        .CommentItem-headWrapper {
            position: relative;
        }
        .CommentItem-inlineReply {
            padding: 20px 0;
        }
        .CommentItem-conversationButton {
            position: absolute;
            right: 0;
            height: 24px;
            line-height: 24px;
        }
        .CommentItem-replySplit {
            margin: 0 6px;
        }
        .CommentItem-headWrapper a {
            color: #333;
            font-weight: 600;
            font-synthesis: style;
        }
        .CommentItem-content {
            min-height: 22px;
            margin: 8px 0;
            font-size: 15px;
            line-height: 24px;
            word-break: break-word;
        }
        .CommentItem-foot {
            display: flex;
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
            color: gray;
            font-size: 14px;
            line-height: 30px;
        }
        .CommentItem-head {
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
             line-height: 24px;
        }
        .CommentItem-context {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .CommentItem-headWrapper a {
            color: #333;
            font-weight: 600;
            font-synthesis: style;
        }
        .CommentItem-like {
            position: absolute;
            right: 0;
        }
        .HoverTitle {
            position: relative;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-box-align: stretch;
            -ms-flex-align: stretch;
            align-items: stretch;
            -ms-flex-negative: 0;
            flex-shrink: 0;
        }
        .HoverTitle:before {
            top: 22px;
            width: 0;
            height: 0;
            margin: 0 0 0 -6px;
            font-size: 0;
            color: rgba(0,0,0,.8);
            border-bottom: 6px solid currentColor;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
        }
        .HoverTitle:after {
            content: attr(data-hover-title);
            top: 28px;
            padding: 10px 16px;
            border-radius: 4px;
            line-height: 1.5;
            font-size: 13px;
            color: #fff;
            background: rgba(0,0,0,.8);
            -webkit-transform: translateX(-50%);
            transform: translateX(-50%);
            width: 80px;
        }
        .HoverTitle:after, .HoverTitle:before {
            content: "";
            position: absolute;
            left: 50%;
            visibility: hidden;
            opacity: 0;
            z-index: 10;
        }
        .CommentItem-action {
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            height: 30px;
            line-height: 30px;
            /* display: none; */
            margin-left: 8px;
        }
        .PostComment-split {
            font-size: 14px;
            margin: 32px auto 0;
            width: 500px;
            text-align: center;
        }
        .PostComment-split .BlockTitle-title {
            padding: 0 16px;
            font-weight: 400;
            color: gray;
        }
        .ConversationDialog-list {
            max-height: calc(60vh - 80px);
            overflow: auto;
            padding-bottom: 40px;
        }
        .ConversationDialog-list .CommentItem {
            padding-right: 16px;
        }
        .ConversationDialog-list .CommentItem:first-child {
            margin-top: 0;
        }
        .commentModal{
            padding-right: 17px;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            /* z-index: 203; */
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            overflow-x: hidden;
            overflow-y: auto;
            -webkit-transition: opacity .3s ease-out;
            transition: opacity .3s ease-out;
        }
        .commentModal .modal-dialog{
            position: relative;
            z-index: 1;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            /* width: 400px; */
            max-height: calc(100vh - 48px);
            margin-right: auto;
            margin-left: auto;
            outline: 0;
            -webkit-box-shadow: 0 5px 20px rgba(26,26,26,.1);
            box-shadow: 0 5px 20px rgba(26,26,26,.1);
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            -webkit-transition: max-height .8s ease;
            transition: max-height .8s ease;
        }
        .commentModal .modal-content {
            padding: 0 24px 32px;
        }
        .commentModal .modal-header {
            padding: 15px;
             border-bottom: none;
        }
    </style>
@endsection

@section('content')
    <main class="main_content col-md-12 no-padding clearfix">
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
                <div class="PostComment">
                    <div class="BlockTitle av-marginLeft av-borderColor PostComment-blockTitle">
                        <span class="BlockTitle-title"><!-- react-text: 84 -->760 条评论<!-- /react-text --></span>
                        <span class="BlockTitle-line"></span>
                    </div>
                    <div class="CommentEditor PostComment-mainEditor">
                        <img class="Avatar-hemingway CommentEditor-avatar Avatar--xs" alt="小小梦工场" src="https://pic1.zhimg.com/d18389ea3_xs.jpg" srcset="https://pic1.zhimg.com/d18389ea3_l.jpg 2x">
                        <div class="CommentEditor-input">
                            <div class="Input-wrapper Input-wrapper--spread Input-wrapper--large Input-wrapper--noPadding">
                                <textarea class="richText form-control" name="postComment" id="comment" rows="2" placeholder="评论由作者筛选后显示"></textarea>
                            </div>
                            <div class="CommentEditor-actions">
                                <button class="Button Button--plain" type="button">取消</button>
                                <button class="Button Button--blue" disabled="" type="button">评论</button>
                            </div>
                        </div>
                    </div>
                    <div class="PostCommentList">
                        <div class="CommentItem">
                            <a class="UserAvatar CommentItem-author" href="https://www.zhihu.com/people/cheneyfm" target="_blank"><img class="Avatar-hemingway Avatar--xs" alt="文煦" src="https://pic1.zhimg.com/cb7156696_xs.jpg" srcset="https://pic1.zhimg.com/cb7156696_l.jpg 2x"></a>
                            <div class="CommentItem-headWrapper">
                                <button class="Button CommentItem-conversationButton Button--plain" type="button" data-toggle="modal" data-target="#commentModal">
                                    <i class="fa fa-comments"></i> 查看对话
                                </button>
                                <div class="CommentItem-head">
                                    <span class="CommentItem-context">
                                        <a href="https://www.zhihu.com/people/gu-rui-80" class="" target="_blank">白鸟</a>
                                        <span class="CommentItem-replyTo">
                                            <span class="CommentItem-replySplit">回复</span>
                                            <a href="https://www.zhihu.com/people/guo-zhi-89-43" class="" target="_blank">沉浮世</a>
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div class="CommentItem-content">有的家长的宠溺连孩子的成绩都不求，不能理解</div>
                            <div class="CommentItem-foot">
                                <span class="CommentItem-like" title="382 人觉得这个很赞">382 赞</span>
                                <div class="HoverTitle CommentItem-createdTime" data-hover-title="2017 年 11月 27 日星期一晚上 11 点 50 分">
                                    <time datetime="Mon Nov 27 2017 23:50:54 GMT+0800 (中国标准时间)">2 个月前</time>
                                </div>
                                <button class="Button CommentItem-action CommentItem-actionReply Button--plain" type="button"><i class="fa fa-reply"></i>回复</button>
                                <button class="Button CommentItem-action CommentItem-actionLike Button--plain" type="button"><i class="fa fa-thumbs-o-up"></i>赞</button>
                            </div>
                            <div class="CommentEditor CommentItem-inlineReply PostComment-mainEditor">
                                <img class="Avatar-hemingway CommentEditor-avatar Avatar--xs" alt="小小梦工场" src="https://pic1.zhimg.com/d18389ea3_xs.jpg">
                                <div class="CommentEditor-input">
                                    <div class="Input-wrapper Input-wrapper--spread Input-wrapper--large Input-wrapper--noPadding">
                                        <textarea class="richText form-control" name="postComment" id="comment" rows="2" placeholder="评论由作者筛选后显示"></textarea>
                                    </div>
                                    <div class="CommentEditor-actions">
                                        <button class="Button Button--plain" type="button">取消</button>
                                        <button class="Button Button--blue" disabled="" type="button">评论</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="BlockTitle av-marginLeft av-borderColor PostComment-split">
                            <span class="BlockTitle-title">以上为精选评论</span>
                            <span class="BlockTitle-line"></span>
                        </div>
                        {{--分页--}}
                        <div class="Pagination PostComment-pagination">
                            <button class="Button PaginationButton PaginationButton--current Button--plain" disabled="" type="button"><!-- react-text: 98 -->1<!-- /react-text --></button><button class="Button PaginationButton Button--plain" type="button"><!-- react-text: 100 -->2<!-- /react-text --></button><button class="Button PaginationButton Button--plain" type="button"><!-- react-text: 102 -->3<!-- /react-text --></button><button class="Button PaginationButton Button--plain" type="button"><!-- react-text: 104 -->4<!-- /react-text --></button><span class="PaginationButton PaginationButton--fake">...</span><button class="Button PaginationButton Button--plain" type="button"><!-- react-text: 107 -->76<!-- /react-text --></button><button class="Button PaginationButton PaginationButton-next Button--plain" type="button"><!-- react-text: 109 -->下一页<!-- /react-text --></button>
                        </div>
                    </div><!-- react-empty: 110 --><!-- react-empty: 111 -->
                </div>
            </div>
        </div>
    </main>
    <div class="commentModal modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" style="display: none">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="commentModalLabel" style="text-align: center;font-size: 24px">查看对话</h4>
                </div>
                <div class="modal-body">
                    <div class="ConversationDialog">
                        <div>
                            <div class="ConversationDialog-list">
                                <div class="CommentItem">
                                    <a class="UserAvatar CommentItem-author" href="https://www.zhihu.com/people/cheneyfm" target="_blank"><img class="Avatar-hemingway Avatar--xs" alt="文煦" src="https://pic1.zhimg.com/cb7156696_xs.jpg" srcset="https://pic1.zhimg.com/cb7156696_l.jpg 2x"></a>
                                    <div class="CommentItem-headWrapper">
                                        <div class="CommentItem-head">
                                    <span class="CommentItem-context">
                                        <a href="https://www.zhihu.com/people/gu-rui-80" class="" target="_blank">白鸟</a>
                                        <span class="CommentItem-replyTo">
                                            <span class="CommentItem-replySplit">回复</span>
                                            <a href="https://www.zhihu.com/people/guo-zhi-89-43" class="" target="_blank">沉浮世</a>
                                        </span>
                                    </span>
                                        </div>
                                    </div>
                                    <div class="CommentItem-content">有的家长的宠溺连孩子的成绩都不求，不能理解</div>
                                    <div class="CommentItem-foot">
                                        <span class="CommentItem-like" title="382 人觉得这个很赞">382 赞</span>
                                        <div class="HoverTitle CommentItem-createdTime" data-hover-title="2017 年 11月 27 日星期一晚上 11 点 50 分">
                                            <time datetime="Mon Nov 27 2017 23:50:54 GMT+0800 (中国标准时间)">2 个月前</time>
                                        </div>
                                        {{--<button class="Button CommentItem-action CommentItem-actionReply Button--plain" type="button"><i class="fa fa-reply"></i>回复</button>--}}
                                        <button class="Button CommentItem-action CommentItem-actionLike Button--plain" type="button"><i class="fa fa-thumbs-o-up"></i>赞</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
@endsection