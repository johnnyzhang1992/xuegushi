@extends('frontend.layout.author')

@section('content-css')
    <style>
        .poem-card .poem-tool {
             padding-bottom: 0;
             margin-bottom: 0;
             border-bottom: none
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
                <div class="topTypeHeader">
                    <div class="typeHeaderItem" style="margin-bottom: 10px">
                        <div class="topTypeHeader-nav top">
                            @if(isset($dynasty) || isset($type) || isset($tag))
                                @if(isset($dynasty) && $dynasty)
                                    @if($dynasty != 'all')
                                        <span>{{ @$dynasty }}</span>
                                    @endif
                                @endif
                                @if(isset($type) && $type)
                                    @if($type != 'all')
                                        <span>{{ @$type }}</span>
                                    @endif
                                @endif
                                @if(isset($tag) && $tag)
                                    <span>{{@$tag}}</span>
                                @endif
                            @else
                                <span>不限</span>
                            @endif
                        </div>
                    </div>
                    <div class="typeHeaderItem">
                        <a role="button" class="topTypeHeader-rightItem pull-left">朝代</a>
                        <div class="topTypeHeader-nav">
                            <a href="{{url('author?dynasty=all')}}" class="topTypeHeader-navItem @if(isset($dynasty) && $dynasty == 'all') active @endif">不限</a>
                            @if(isset($dynastys) && $dynastys)
                                @foreach($dynastys as $p_dy)
                                    <a href="{{url('author?dynasty='.$p_dy->alia_name)}}" class="topTypeHeader-navItem @if(isset($dynasty) && $dynasty ==$p_dy->alia_name) active @endif">{{ $p_dy->alia_name }}</a>
                                @endforeach
                            @endif

                        </div>
                    </div>
                </div>
                @if($authors->total() &&$authors->total()>0 )
                    <div class="nav-breadcrumb" style="margin-bottom: 15px">
                        <ol class="breadcrumb" style="margin-bottom: 0;background-color: #fff">
                            <li class="active">当前页：{{@$authors->currentPage()}}</li>
                            <li class="active">总页数：{{@$authors->lastPage()}}</li>
                            <li>共 {{ @$authors->total() }} 条</li>
                        </ol>
                    </div>
                @endif
                @if(isset($authors) && $authors)
                    @foreach($authors as $author)
                        <div class="poem-card">
                            @if(file_exists('static/author/'.@$author->author_name.'.jpg'))
                                <div class="author-avatar pull-left" style="margin-bottom: 15px;margin-right: 15px;width: 105px;height: 150px;">
                                    <a href="{{ url('author/'.@$author->id) }}" style="display: block;">
                                        <img src="{{ asset('/static/author/'.@$author->author_name.'.jpg') }}" alt="">
                                    </a>
                                </div>
                            @endif
                            <div class="author-name">
                                <p>
                                    <a href="{{ url('author/'.@$author->id) }}" style="font-size: 18px">{{ @$author->author_name }}</a>
                                </p>
                            </div>
                            <div class="poem-content" style="min-height: 130px;">
                                {!! @$author->profile !!}
                            </div>
                            <div class="poem-tool clearfix">
                                @if(isset($author->collect_status) && $author->collect_status == 'active')
                                    <div class="collect active" data-toggle="tooltip" data-placement="top" title="收藏" data-type="author" data-id="{{$author->id}}">
                                        <i class="fa fa-star"></i> <span class="tool-name">已收藏</span>
                                    </div>
                                @else
                                    <div class="collect" data-toggle="tooltip" data-placement="top" title="收藏" data-type="author" data-id="{{$author->id}}">
                                        <i class="fa  fa-star-o"></i> <span class="tool-name">收藏</span>
                                    </div>
                                @endif
                                @if(isset($author->status) && $author->status == 'active')
                                    <div class="like pull-right active" data-toggle="tooltip" data-placement="top" title="喜欢" data-type="author" data-id="{{@$author->id}}">
                                        <i class="fa fa-thumbs-o-up"></i> <span class="like_count">{{@$author->like_count}}</span>
                                    </div>
                                @else
                                    <div class="like pull-right" data-toggle="tooltip" data-placement="top" title="喜欢" data-type="author" data-id="{{@$author->id}}">
                                        <i class="fa fa-thumbs-o-up"></i> <span class="like_count">{{@$author->like_count}}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="tool-qrcode">

                            </div>
                        </div>
                    @endforeach
                        {{@$authors->links()}}
                @endif
            </div>
            {{--right--}}
            <div class="main_right col-md-4">
                @include('frontend.partials.author_side')
                @include('frontend.partials.footer')
            </div>
        </div>
    </main>
@endsection

@section('content-js')

@endsection