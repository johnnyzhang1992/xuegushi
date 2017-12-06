@extends('frontend.layout.poem')

@section('content-css')

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
                    <div class="typeHeaderItem" style="margin-bottom: 10px">

                        <a role="button" class="topTypeHeader-rightItem pull-left">类型</a>
                        <div class="topTypeHeader-nav">
                            @if(isset($tag) && $tag)
                                <a href="{{url('poem?type=all&dynasty='.$dynasty.'&tag='.$tag)}}" class="topTypeHeader-navItem @if(isset($type) && $type == 'all') active @endif">不限</a>
                            @else
                                <a href="{{url('poem?type=all&dynasty='.$dynasty)}}" class="topTypeHeader-navItem @if(isset($type) && $type == 'all') active @endif">不限</a>
                            @endif

                            @foreach($poem_types as $p_type)
                                @if(isset($tag) && $tag)
                                    @if(isset($dynasty) && $dynasty)
                                        <a href="{{url('poem?type='.$p_type->alia_name.'&dynasty='.$dynasty.'&tag='.$tag)}}" class="topTypeHeader-navItem @if(isset($type) && $type ==$p_type->alia_name) active @endif">{{ $p_type->alia_name }}</a>
                                    @else
                                        <a href="{{url('poem?type='.$p_type->alia_name.'&tag='.$tag)}}" class="topTypeHeader-navItem @if(isset($type) && $type ==$p_type->alia_name) active @endif">{{ $p_type->alia_name }}</a>
                                    @endif
                                @else
                                    @if(isset($dynasty) && $dynasty)
                                        <a href="{{url('poem?type='.$p_type->alia_name.'&dynasty='.$dynasty)}}" class="topTypeHeader-navItem @if(isset($type) && $type ==$p_type->alia_name) active @endif">{{ $p_type->alia_name }}</a>
                                    @else
                                        <a href="{{url('poem?type='.$p_type->alia_name)}}" class="topTypeHeader-navItem @if(isset($type) && $type ==$p_type->alia_name) active @endif">{{ $p_type->alia_name }}</a>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="typeHeaderItem">
                        <a role="button" class="topTypeHeader-rightItem pull-left">朝代</a>
                        <div class="topTypeHeader-nav">
                            @if(isset($type) && $type)
                                <a href="{{url('poem?type='.$type.'&dynasty=all'.'&tag='.$tag)}}" class="topTypeHeader-navItem @if(isset($dynasty) && $dynasty == 'all') active @endif">不限</a>
                            @else
                                <a href="{{url('poem?type='.$type.'&dynasty=all')}}" class="topTypeHeader-navItem @if(isset($dynasty) && $dynasty == 'all') active @endif">不限</a>
                            @endif
                            @foreach($poem_dynasty as $p_dy)
                                @if(isset($tag) && $tag)
                                    @if(isset($type) && $type)
                                        <a href="{{url('poem?dynasty='.$p_dy->alia_name.'&type='.$type.'&tag='.$tag)}}" class="topTypeHeader-navItem @if(isset($dynasty) && $dynasty ==$p_dy->alia_name) active @endif">{{ $p_dy->alia_name }}</a>
                                    @else
                                        <a href="{{url('poem?dynasty='.$p_dy->alia_name.'&tag='.$tag)}}" class="topTypeHeader-navItem @if(isset($dynasty) && $dynasty ==$p_dy->alia_name) active @endif">{{ $p_dy->alia_name }}</a>
                                    @endif
                                @else
                                    @if(isset($type) && $type)
                                        <a href="{{url('poem?dynasty='.$p_dy->alia_name.'&type='.$type)}}" class="topTypeHeader-navItem @if(isset($dynasty) && $dynasty ==$p_dy->alia_name) active @endif">{{ $p_dy->alia_name }}</a>
                                    @else
                                        <a href="{{url('poem?dynasty='.$p_dy->alia_name)}}" class="topTypeHeader-navItem @if(isset($dynasty) && $dynasty ==$p_dy->alia_name) active @endif">{{ $p_dy->alia_name }}</a>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @if($poems->total() &&$poems->total()>0 )
                    <div class="nav-breadcrumb" style="margin-bottom: 15px">
                        <ol class="breadcrumb" style="margin-bottom: 0;background-color: #fff">
                            <li class="active">当前页：{{@$poems->currentPage()}}</li>
                            <li class="active">总页数：{{@$poems->lastPage()}}</li>
                            <li>共 {{ @$poems->total() }} 条结果</li>
                        </ol>
                    </div>
                @endif
                @if(isset($poems) && count($poems)>0)
                    @foreach($poems as $poem)
                        <div class="poem-card">
                            <div id="poem-c-{{@$poem->id}}">
                                <div class="card-title">
                                    <h2 class="poem-title">
                                        <a class="title-link" href="{{ url('poem/'.$poem->id) }}" target="_blank">{{@$poem->title}}</a>
                                    </h2>
                                </div>
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
                                            @foreach(json_decode($poem->content)->content as $item)
                                                <p class="p-content">{!! @$item !!}</p>
                                            @endforeach
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="poem-tool clearfix">
                                <div class="collect @if(isset($poem->collect_status) && $poem->collect_status == 'active') active @endif" data-toggle="tooltip" data-placement="top" title="收藏" data-type="poem" data-id="{{$poem->id}}">
                                    <i class="fa  @if(isset($poem->collect_status) && $poem->collect_status == 'active') fa-heart @else fa-heart-o @endif"></i>
                                </div>
                                <div class="copy" data-toggle="tooltip" data-placement="top" title="复制"  data-clipboard-action="copy" data-clipboard-target="#poem-c-{{@$poem->id}}">
                                    <i class="fa fa-clone"></i>
                                </div>
                                <div class="speaker" data-toggle="tooltip" data-placement="top" title="朗读" data-status="" data-type="poem" data-id="{{$poem->id}}">
                                    <i class="fa fa-microphone" aria-hidden="true"></i>
                                </div>
                                <div class="like pull-right @if(isset($poem->status) && $poem->status == 'active') active @endif" data-toggle="tooltip" data-placement="top" title="喜欢" data-type="poem" data-id="{{$poem->id}}">
                                    <i class="fa fa-thumbs-o-up"></i> <span class="like_count">{{@$poem->like_count}}</span>
                                </div>
                            </div>
                            <div id="speaker-{{$poem->id}}" class="poem-speaker" style="clear:both; height:auto; margin-top:10px; margin-bottom:10px; overflow:hidden;display: none">

                            </div>
                            <div class="tool-qrcode">

                            </div>
                            <div class="poem-tag">
                                <p>
                                    @if(isset($poem->tags) && $poem->tags)
                                        @foreach(explode(',',$poem->tags) as $key=>$tag1)
                                            @if($key+1 < count(explode(',',$poem->tags)))<a href="{{ url('poem?tag='.$tag1) }}" class="tag @if(isset($tag) && $tag && $tag == $tag1 ) active @endif">{{@$tag1}} ,</a>@else<a href="" class="tag">{{@$tag1}}</a>@endif
                                        @endforeach
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                @endif
                {{@$poems->links()}}
            </div>
            {{--right--}}
            <div class="main_right col-md-4">
                @include('frontend.partials.side')
                @include('frontend.partials.footer')
            </div>
        </div>
    </main>
@endsection

@section('content-js')

@endsection