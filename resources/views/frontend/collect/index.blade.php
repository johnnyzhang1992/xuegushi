<?php
use App\Helpers\DateUtil;
?>
@extends('frontend.layout.collect')

@section('content-css')
    <style>
        .main_content {
             background-color: #fff;
        }
        .pane-content {
            padding: 0 10px 0 10px;
        }
        .count-card{
            margin-bottom: 0;
        }
        p.p-content {
            margin-bottom: 0;
            color: #4e4e4e;
        }
        .side-card .side-list{
            font-size: 14px;
        }
        .side-card .side-list .SideBar-navLink{
            padding-left: 0;
            height: 30px;
        }
        .collect-list .collect-item-title{
            font-size: 16px;
            margin-top: 10px;
        }
        .collect-item {
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        a.author_name,a.author_dynasty {
            color: #999;
        }
        .collect-item-meta,.uncollect{
            color: #999;
        }
        .collect-item-meta .badge{
            background-color: #999;
        }
        .zg-unfollow {
            color: #999;
        }
        .hide{
            display: none;
        }
        a.toggle-expand{
            cursor: pointer;
        }

        @media (max-width: 375px) {
            .main_left .nav > li > a{
                padding: 5px 10px;
            }
        }
    </style>
@endsection

@section('header')
    {{--header--}}
    @include('frontend.partials.header')
@endsection

@section('content')
    {{--main-content--}}
    <main class="main_content col-md-12 no-padding">
        <div class="content col-md-9">
            {{--left--}}
            <div class="main_left col-md-8">
                <div class="nav-breadcrumb" style="margin-bottom: 15px">
                    <ol class="breadcrumb" style="margin-bottom: 0;background-color: #fff">
                        <li><a href="{{ url('/') }}">首页</a></li>
                        <li class="active">我的收藏</li>
                    </ol>
                </div>
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" @if(!isset($type)) class="active" @endif>
                            <a href="#poem" aria-controls="poem" role="tab" data-toggle="tab">收藏的古诗文(<span class="collect_count">{{@$poems->total()}}</span>条)</a>
                        </li>
                        <li role="presentation" @if(isset($type)) class="active" @endif>
                            <a href="#author" aria-controls="author" role="tab" data-toggle="tab">收藏的作者(<span class="author_count">{{$authors->total()}}</span>条)</a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane @if(!isset($type)) active @endif" id="poem">
                           <div class="pane-content">
                               @if(isset($poems) && count($poems)>0)
                                   @foreach($poems as $poem)
                                       <div class="collect-list">
                                           <div class="collect-item" id="p-{{@$poem->like_id}}">
                                               <h2 class="collect-item-title">
                                                   <a class="title-link" href="{{ url('poem/'.$poem->like_id) }}" target="_blank">{{@$poem->title}}</a>
                                               </h2>
                                               <div class="poem-author">
                                                   <p>
                                                       <a class="author_dynasty" href="{{ url('/poem?dynasty='.@$poem->dynasty) }}" target="_blank">{{@$poem->dynasty}}</a> : <a class="author_name" @if(isset($poem->author_id) && $poem->author_id != -1)href="{{ url('/author/'.@$poem->author_id) }}" @endif target="_blank">{{@$poem->author}}</a>
                                                   </p>
                                               </div>
                                               <div class="poem-content">
                                                   @if(isset($poem->content) && json_decode($poem->content))
                                                       @if(isset(json_decode($poem->content)->xu) && json_decode($poem->content)->xu)
                                                           <p class="poem-xu">{{ @json_decode($poem->content)->xu }}</p>
                                                       @endif
                                                       @if(isset(json_decode($poem->content)->content) && json_decode($poem->content)->content)
                                                               @foreach(json_decode($poem->content)->content as $key=>$item)
                                                                   @if($key == 2)
                                                                       <a type="button" class="toggle-expand" title="点此查看原文详情" >查看《{{ @$poem->title }}》全文</a>
                                                                       <p class="p-content hide">{!! @$item !!}</p>
                                                                   @elseif($key >1)
                                                                       <p class="p-content hide">{!! @$item !!}</p>
                                                                   @else
                                                                       <p class="p-content">{!! @$item !!}</p>
                                                                   @endif
                                                               @endforeach
                                                       @endif
                                                   @endif
                                               </div>
                                               <div class="collect-item-meta">
                                                   <span>收藏 <span class="badge">{{@$poem->collect_count}}</span></span>
                                                   <span class="zg-bull zu-autohide">•</span>
                                                   <span>喜欢 <span class="badge">{{@$poem->like_count}}</span></span>
                                                   <span class="zg-bull zu-autohide">•</span>
                                                   <span>创建时间 {{@ DateUtil::formatDate(strtotime($poem->created_at))}}</span>
                                                   <span class="zg-bull zu-autohide">•</span>
                                                   <a href="javascript:;" class="uncollect" data-id="{{@$poem->like_id}}" data-type="poem" name="unfavo">取消收藏</a>
                                               </div>
                                           </div>
                                       </div>
                                   @endforeach()
                               @endif
                               {{@$poems->links()}}
                           </div>
                        </div>
                        <div role="tabpanel" class="tab-pane @if(isset($type)) active @endif" id="author">
                            <div class="pane-content">
                                @if(isset($authors) && count($authors)>0)
                                    @foreach($authors as $author)
                                        <div class="collect-list">
                                            <div class="collect-item" id="a-{{@$author->like_id}}">
                                                <h2 class="collect-item-title">
                                                    <a class="title-link" href="{{ url('author/'.$author->like_id) }}" target="_blank">{{@$author->author_name}}</a>
                                                </h2>
                                                <div class="author-author">
                                                    <p>
                                                        <a class="author_dynasty" href="{{ url('/author?dynasty='.@$author->dynasty) }}" target="_blank">朝代：{{@$author->dynasty}}</a>
                                                    </p>
                                                </div>
                                                <div class="collect-item-meta">
                                                    <span>收藏 <span class="badge">{{@$author->collect_count}}</span></span>
                                                    <span class="zg-bull zu-autohide">•</span>
                                                    <span>喜欢 <span class="badge">{{@$author->like_count}}</span></span>
                                                    <span class="zg-bull zu-autohide">•</span>
                                                    <span>创建时间 {{@ DateUtil::formatDate(strtotime($author->created_at))}}</span>
                                                    <span class="zg-bull zu-autohide">•</span>
                                                    <a href="javascript:;" class="uncollect" data-id="{{@$author->like_id}}" data-type="author" name="unfavo">取消收藏</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach()
                                @endif
                                {{@$authors->links()}}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            {{--right--}}

            <div class="main_right col-md-4">
                <div class="side-card count-card">
                    <ul class="side-list">
                        <li class="list-item">
                            <a class="Button SideBar-navLink Button--plain" href="{{ url('collections') }}" target="_blank" type="button">
                                <i class="fa fa-star-o"></i> <span class="SideBar-navText">我的收藏</span>
                                <span class="SideBar-navNumber">{{@$collect_count}}</span>
                            </a>
                        </li>
                        <li class="list-item">
                            <a class="Button SideBar-navLink Button--plain" href="#" target="_blank" type="button" >
                                <i class="fa fa-thumbs-o-up"></i> <span class="SideBar-navText">我的喜欢</span>
                                <span class="SideBar-navNumber">{{@$like_count}}</span>
                            </a>
                        </li>
                    </ul>
                </div>
                @include('frontend.partials.footer')
            </div>
        </div>
    </main>
@endsection

@section('content-js')
    <script>
        $('.toggle-expand').on('click',function(){
            $(this).parent().find('.hide').removeClass('hide');
            $(this).remove()
        });
        $('.uncollect').on('click',function () {
            var type = $(this).attr('data-type');
            var id = $(this).attr('data-id');
            $.post(
                '/ajax/update/collect',
                {
                    'id': id,
                    'type': type,
                    '_token': $('input[name="_token"]').val()
                },
                function (res) {
                    if(res && res.status == 'delete'){
                        if(type == 'poem'){
                            $('#p-'+id).slideUp("slow", function() {
                                $(this).remove();
                            });
                        }else{
                            $('#a-'+id).slideUp("slow", function() {
                                $(this).remove();
                            });
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