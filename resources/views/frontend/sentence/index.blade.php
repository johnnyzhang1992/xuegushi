@extends('frontend.layout.poem')
@section('header')
    {{--header--}}
    @include('frontend.partials.header')
@endsection

@section('content-css')
    <style>
        .main_content{
            background-color: #fff;
        }
        .SentenceItemList{
            position: relative;
        }
        .sentenceItem .sentenceTitle{
            font-weight: 500;
            color: #333;
            font-size: 17px;
            line-height: 1.78em;
            padding-bottom: 8px;
            padding-top: 20px;
            margin-bottom: 0;
            margin-top: 0;
            cursor: pointer;
            position: relative;
        }
        @media (max-width: 736px) {
            .sentenceItem .sentenceTitle .origin,.sentenceItem .sentenceTitle .title-link{
                display: block;
                width: 100%;
            }
            .sentenceTool .tags{
                max-width: 70%;
            }
        }
        @media (max-width: 375px) {
            .sentenceTool .tags{
                max-width: 65%;
            }

        }
        @media (max-width: 325px) {
            .sentenceTool .tags{
                max-width: 60%;
            }
        }
        .sentenceItem .origin{
            font-size: 14px;
            float: right;
        }
        .sentenceItem .sentenceContent, .sentenceItem .origin{
            color: #999;
        }
        .sentenceItem .s-content,.sentenceItem .origin{
            cursor: pointer;
        }
        .sentenceItem.sentenceContent:hover, .sentenceItem .origin:hover{
            color: #737373;
        }
        .sentenceItem{
            margin-bottom: 10px;
            border-bottom: 1px solid #e6e6e6;
        }
        .sentenceContent {
            padding-bottom: 10px;
        }
        .footer {
            padding-left: 15px;
        }
        .sentenceTool{
            font-size: 14px;
            padding-top: 6px;
        }
        .sentenceTool a{
            color: #737373;
        }
        .sentenceTool  .like,.sentenceTool .collect{
            cursor: pointer;
        }
        .sentenceTool  .like.active,.sentenceTool .collect.active {
            color: #337ab7;
        }
        .sentenceTool a:hover,.sentenceMeta a:hover{
            color: #337ab7;
        }
        .sentenceTool .tags{
            margin-left: 10px;
        }
        .sentenceTool .fa{
            font-size: 18px;
        }
        .sentenceMeta{
            padding-top: 8px;
            text-align: right;
        }
        .sentenceMeta a{
            color: #737373;
        }
        .sentenceMeta  span {
            position: relative;
            top: 0;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 4px 0 4px 6px;
            border-color: transparent transparent transparent #b2b2b2;
            display: inline-block;
            margin-left: 6px;
        }
        .sentenceItem.selected {
            border: 1px solid #e6e6e6;
            -webkit-transform: scale(1.05);
            -moz-transform: scale(1.05);
            transform: scale(1.05);
            padding: 0 15px;
            border-radius: 8px;
            box-shadow: 0 4px 18px -4px rgba(0,0,0,.1);
        }
        .topTypeHeader-rightItem{
            font-size: 18px;
            margin-right: 10px;
            color: #737373;
        }
    </style>
@endsection
@section('content')
    {{--main-content--}}
    <main class="main_content col-md-12 no-padding">
        <div class="content col-md-9">
            {{--left--}}
            <div class="main_left col-md-8">
                <div class="nav-breadcrumb">
                    <ol class="breadcrumb" style="margin-bottom: 0;background-color: #fff;padding-left: 0">
                        <li>当前位置</li>
                        <li>名句</li>
                        @if(isset($theme)&& $theme)
                            <li>{{@$theme}}</li>
                        @endif
                        @if(isset($type) && $type)
                            <li>{{ @$type }}</li>
                        @endif
                    </ol>
                </div>
                <div class="topTypeHeader">
                    <div class="typeHeaderItem" style="padding-bottom: 10px;">
                        <span role="button" class="topTypeHeader-rightItem pull-left">主题:</span>
                        <div class="topTypeHeader-nav">
                            <a href="{{url('sentence')}}" class="topTypeHeader-navItem @if(!isset($theme)) active @endif">全部</a>
                            @foreach($themes as $_theme)
                                <a href="{{url('sentence?theme='.$_theme)}}" class="topTypeHeader-navItem @if(isset($theme) && $theme == $_theme) active @endif">{{ $_theme }}</a>
                            @endforeach
                        </div>
                    </div>
                    @if(isset($types) && count($types)>0)
                        <div class="typeHeaderItem" style="padding-bottom: 10px;border-bottom: 1px solid #e6e6e6;">
                            <span role="button" class="topTypeHeader-rightItem pull-left">分类:</span>
                            <div class="topTypeHeader-nav">
                                <a href="{{url('sentence?theme='.$theme)}}" class="topTypeHeader-navItem @if(!isset($type)) active @endif">全部</a>
                                @foreach($types as $_type)
                                    <a href="{{url('sentence?theme='.@$theme.'&type='.@$_type)}}" class="topTypeHeader-navItem @if(isset($type) && $type == $_type) active @endif">{{ $_type }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                @if($sentences->total() &&$sentences->total()>0 )
                    <div class="nav-breadcrumb">
                        <ol class="breadcrumb" style="margin-bottom: 0;background-color: #fff;padding-left: 0">
                            <li class="active">当前页：{{@$sentences->currentPage()}}</li>
                            <li class="active">总页数：{{@$sentences->lastPage()}}</li>
                            <li>共 {{ @$sentences->total() }} 条</li>
                        </ol>
                    </div>
                @endif
                @if(isset($sentences) && count($sentences)>0)
                    <div class="SentenceItemList">
                        @foreach($sentences as $sentence)
                            <div class="sentenceItem" id="sentence-{{@$sentence->id}}">
                                <h2 class="sentenceTitle">
                                    <a  href="{{url('poem/'.@$sentence->poem_id)}}" class="title-link" target="_blank">{{@$sentence->title}}</a>
                                    <a class="origin" target="_blank" data-toggle="tooltip" data-placement="bottom" title="{{@$sentence->dynasty.':'.@$sentence->author}}">{{$sentence->author.'《'.@$sentence->poem_title.'》'}}</a>
                                </h2>
                                <div class="sentenceContent">
                                    <div class="s-content" data-id="{{@$sentence->id}}">{!! @$sentence->content !!}</div>
                                    <div class="sentenceTool poem-tool clearfix" style="display: none">
                                        @if(isset($sentence->collect_status) && $sentence->collect_status == 'active')
                                            <div class="collect pull-left active"  title="收藏" data-type="sentence" data-id="{{@$sentence->id}}">
                                                <i class="fa fa-star"></i> 收藏
                                            </div>
                                        @else
                                            <div class="collect pull-left" title="收藏" data-type="sentence" data-id="{{@$sentence->id}}">
                                                <i class="fa  fa-star-o"></i> 收藏
                                            </div>
                                        @endif
                                        <div class="tags pull-left">
                                            <i class="fa fa-tag"></i>
                                            @if(isset($sentence->tags) && $sentence->tags)
                                                @foreach(explode(',',$sentence->tags) as $key=>$tag1)
                                                    @if($key+1 < count(explode(',',$sentence->tags)))<a href="{{ url('poem?tag='.$tag1) }}" class="tag @if(isset($tag) && $tag && $tag == $tag1 ) active @endif">{{@$tag1}} ,</a>@else<a href="{{ url('poem?tag='.$tag1) }}" class="tag @if(isset($tag) && $tag && $tag == $tag1 ) active @endif">{{@$tag1}}</a>@endif
                                                @endforeach
                                            @endif
                                        </div>
                                        @if(isset($sentence->status) && $sentence->status == 'active')
                                            <div class="like pull-right active" title="喜欢" data-type="sentence" data-id="{{@$sentence->id}}">
                                                <i class="fa fa-thumbs-o-up"></i> <span class="like_count">{{@$sentence->like_count}}</span>
                                            </div>
                                        @else
                                            <div class="like pull-right" title="喜欢" data-type="sentence" data-id="{{@$sentence->id}}">
                                                <i class="fa fa-thumbs-o-up"></i> <span class="like_count">{{@$sentence->like_count}}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="sentenceMeta clearfix" style="display: none">
                                        <a href="{{url('poem/'.@$sentence->poem_id)}}" target="_blank">查看详情<span></span></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                {{@$sentences->links()}}
            </div>
            {{--right--}}
            <div class="main_right col-md-4">
                @if(isset($hot_poems) && $hot_poems)
                    <div class="side-card">
                        <div class="side-title">
                            <h2><span class="author">代表作品 <small>(作品总数：{{ @$sentences_count }})</small></span></h2>
                        </div>
                        <div class="side-content">
                            <ul style="list-style: none;padding-left: 0">
                                @foreach($hot_poems as $h_poem)
                                    <li>
                                        <a class="" href="{{ url('poem/'.$h_poem->id) }}" target="_blank" style="border: none">{{@$h_poem->title}}</a> <i class="fa fa-thumbs-o-up"></i> {{@$h_poem->like_count}}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                @include('frontend.partials.side')
                @include('frontend.partials.footer')
            </div>
        </div>
    </main>
@endsection

@section('content-js')

@endsection