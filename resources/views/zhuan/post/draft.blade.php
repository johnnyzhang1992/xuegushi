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
        .Drafts-removeButton,.Drafts-resetButton {
            cursor: pointer;
        }
        ul{
            padding: 0;
        }
        .hero-title {
            font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen,Ubuntu,Cantarell,"Open Sans","Helvetica Neue",sans-serif;
            font-weight: 700;
            font-style: normal;
            font-size: 44px;
            margin-left: -2.75px;
            line-height: 1.2;
            letter-spacing: 0;
            color: rgba(0,0,0,0.8);
            margin-bottom: 8px;
            outline: 0;
            word-break: break-word;
            word-wrap: break-word;
        }
    </style>
@endsection

@section('content')
    <main class="main_content col-md-12 no-padding">
        <div class="content col-md-10 col-xs-12 zhuanlan-new">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="container-stream col-md-9 col-md-offset-1 col-xs-12">
                <div class="Layout-main av-card">
                    @if(isset($status) && $status =='drafts')
                        <div class="hero-title">我的草稿</div>
                    @elseif(isset($status) && $status == 'posts')
                        <div class="hero-title">我的文章</div>
                    @endif
                    <div class="InfiniteList Drafts-list">
                        <ul>
                            @if(isset($posts) && $posts)
                                @foreach($posts as $post)
                                    <li class="Drafts-item">
                                        <div class="Drafts-title">
                                            <a class="Drafts-link" href="{{url('post/'.@$post->id.'/edit')}}">{{@$post->title}}</a>
                                        </div>
                                        <div class="Drafts-meta">
                                            <time class="Drafts-updated" data-toggle="tooltip" data-placement="bottom" title="{{@$post->created_at}}">{{@ DateUtil::formatDate(strtotime($post->created_at))}}</time>
                                            @if($post->status != 'delete')
                                                <span class="Bull"></span>
                                                <span class="Drafts-removeButton" data-id="{{@$post->id}}" data-toggle="tooltip" data-placement="bottom" title="删除文章">删除</span>
                                            @else
                                                <span class="Bull"></span>
                                                <span class="Drafts-resetButton" data-id="{{@$post->id}}" data-toggle="tooltip" data-placement="bottom" title="恢复文章">恢复</span>
                                            @endif
                                            <span class="Bull"></span>
                                            @if($post->status == 'active')
                                                <span class="status"  data-toggle="tooltip" data-placement="bottom" title="文章状态">状态：正常</span>
                                            @elseif($post->status == 'draft')
                                                <span class="status"  data-toggle="tooltip" data-placement="bottom" title="文章状态">状态：草稿</span>
                                            @elseif($post->status == 'delete')
                                                <span class="status"  data-toggle="tooltip" data-placement="bottom" title="文章状态">状态：已删除</span>
                                            @endif
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
    <script>
        $('.Drafts-removeButton').on('click',function () {
            var id = $(this).attr('data-id');
            $.ajax({
                url: '/post/delete',
                data:{
                    'id':id,
                    '_token':$('input[name="_token"]').val()
                },
                type: 'POST',
                cache: false,
                success: function (res) {
                    console.log(res);
                    if(res.status == 'success'){
                        $('body').toast({
                            position:'fixed',
                            content:'删除成功！',
                            duration:1000,
                            isCenter:true,
                            background:'rgba(0,0,0,0.5)',
                            animateIn:'bounceIn-hastrans',
                            animateOut:'bounceOut-hastrans'
                        });
                        window.location.reload();
                    }
                },
                error: function (res) {
                    console.log(res);
                }
            });
        });
        $('.Drafts-resetButton').on('click',function () {
            var id = $(this).attr('data-id');
            $.ajax({
                url: '/post/reset',
                data:{
                    'id':id,
                    '_token':$('input[name="_token"]').val()
                },
                type: 'POST',
                cache: false,
                success: function (res) {
                    console.log(res);
                    if(res.status == 'success'){
                        $('body').toast({
                            position:'fixed',
                            content:'恢复状态成功！',
                            duration:1000,
                            isCenter:true,
                            background:'rgba(0,0,0,0.5)',
                            animateIn:'bounceIn-hastrans',
                            animateOut:'bounceOut-hastrans'
                        });
                        window.location.reload();
                    }
                },
                error: function (res) {
                    console.log(res);
                }
            });
        });
    </script>
@endsection