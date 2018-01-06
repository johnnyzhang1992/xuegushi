<?php
use App\Helpers\DateUtil;
?>
@extends('zhuan.layout.zhuanlan')

@section('content-css')
    <style>
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
    </style>
@endsection

@section('content')
    <main class="main_content col-md-12 no-padding">
        <div class="content col-md-10 col-xs-12 zhuanlan-new">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="container-stream col-md-9 col-md-offset-1 col-xs-12">
                <div class="Layout-main av-card">
                    <div class="InfiniteList Drafts-list">
                        <ul>
                            @if(isset($posts) && $posts)
                                @foreach($posts as $post)
                                    <li class="Drafts-item">
                                        <div class="Drafts-title">
                                            <a class="Drafts-link" href="{{url('post/'.@$post->id.'/edit')}}">{{@$post->title}}</a>
                                        </div>
                                        <div class="Drafts-meta">
                                            <time class="Drafts-updated" title="">{{@ DateUtil::formatDate(strtotime($post->created_at))}}</time>
                                            <span class="Bull"></span>
                                            <span class="Drafts-removeButton">删除</span>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div><!-- react-empty: 414 -->
                </div>
            </div>
        </div>
    </main>
@endsection

@section('content-js')

@endsection