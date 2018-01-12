<?php
use App\Helpers\DateUtil;
?>
@extends('zhuan.layout.zhuanlan')

@section('content-css')
    <style>
        .Layout-main {
            margin: 27px auto 0;
            padding: 0;
            width: 660px;
            z-index: 1;
        }
        .FollowersIndex-pageTitle {
            font-size: 17px;
            padding-bottom: 16px;
            font-weight: 400;
            margin-bottom: 16px;
        }
        .FollowersIndex-list {
            padding-bottom: 40px;
        }
        ul{
            padding-left: 0;
        }
        .ListItem, .ListItem-img {
            float: left;
        }
        .ListItem {
            width: 270px;
            margin-right: 60px;
            list-style: none;
            height: 48px;
            margin-bottom: 40px;
            position: relative;
        }
        .ListItem-intro {
            margin-left: 64px;
        }
        .ListItem-img img {
            width: 48px;
            height: 48px;
        }
        .Avatar-hemingway {
            width: 36px;
            height: 36px;
            border-radius: 50%;
        }
        .ListItem-bio {
            display: block;
            color: gray;
            font-size: 14px;
            padding-right: 10px;
            line-height: 20px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .ListItem-name {
            font-size: 16px;
            font-weight: 600;
            font-synthesis: style;
            margin-bottom: 6px;
            display: inline-block;
            line-height: 22px;
        }
        @media (max-width: 420px){
            .Layout-main {
                margin: 0 auto 0;
                padding: 0;
                width: 100%;
                z-index: 1;
            }
            .FollowersIndex-list, .FollowersIndex-pageTitle {
                padding-left: 16px;
                padding-right: 16px;
            }
        }
    </style>
@endsection


@section('content')
    <main class="main_content col-md-12 no-padding">
        <div class="content col-md-9 col-xs-12">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="container-stream col-md-9 col-md-offset-2 col-xs-12 no-padding">
                <div class="Layout-main av-card">
                    <p class="FollowersIndex-pageTitle">{{@$count}} 人关注该专栏</p>
                    <div class="InfiniteList FollowersIndex-list">
                        <ul class="clearfix">
                            @if(isset($followers) && $followers)
                                @foreach($followers as $follower)
                                    <li class="ListItem">
                                        <a class="ListItem-img" href="{{url('people/'.@$follower->id)}}" target="_blank"><img class="Avatar-hemingway Avatar--l" alt="{{@$follower->name}}" src="{{ asset(@$follower->avatar) }}"></a>
                                        <div class="ListItem-intro">
                                            <a href="{{url('people/'.@$follower->id)}}" target="_blank"><strong class="ListItem-name">{{@$follower->name}}</strong></a>
                                            <span class="ListItem-bio">{{@$follower->about}}</span>
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

@endsection