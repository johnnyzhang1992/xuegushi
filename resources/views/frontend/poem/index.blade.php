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
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="_user_id" value="{{ @Auth::user()->id }}">
                <div class="topTypeHeader">
                    <div class="typeHeaderItem" style="margin-bottom: 10px">
                        <a role="button" class="topTypeHeader-rightItem pull-left">类型</a>
                        <div class="topTypeHeader-nav">
                            <a href="{{url('poem?type=all&dynasty='.$dynasty)}}" class="topTypeHeader-navItem @if(isset($type) && $type == 'all') active @endif">不限</a>
                            @foreach($poem_types as $p_type)
                                @if(isset($dynasty) && $dynasty)
                                    <a href="{{url('poem?type='.$p_type->alia_name.'&dynasty='.$dynasty)}}" class="topTypeHeader-navItem @if(isset($type) && $type ==$p_type->alia_name) active @endif">{{ $p_type->alia_name }}</a>
                                @else
                                    <a href="{{url('poem?type='.$p_type->alia_name)}}" class="topTypeHeader-navItem @if(isset($type) && $type ==$p_type->alia_name) active @endif">{{ $p_type->alia_name }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="typeHeaderItem">
                        <a role="button" class="topTypeHeader-rightItem pull-left">朝代</a>
                        <div class="topTypeHeader-nav">
                            <a href="{{url('poem?type='.$type.'&dynasty=all')}}" class="topTypeHeader-navItem @if(isset($dynasty) && $dynasty == 'all') active @endif">不限</a>
                            @foreach($poem_dynasty as $p_dy)
                                @if(isset($type) && $type)
                                    <a href="{{url('poem?dynasty='.$p_dy->alia_name.'&type='.$type)}}" class="topTypeHeader-navItem @if(isset($dynasty) && $dynasty ==$p_dy->alia_name) active @endif">{{ $p_dy->alia_name }}</a>
                                @else
                                    <a href="{{url('poem?dynasty='.$p_dy->alia_name)}}" class="topTypeHeader-navItem @if(isset($dynasty) && $dynasty ==$p_dy->alia_name) active @endif">{{ $p_dy->alia_name }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @if(isset($poems) && count($poems)>0)
                    @foreach($poems as $poem)
                        <div class="poem-card">
                            <div class="card-title">
                                <h2 class="poem-title">
                                    <a class="title-link" href="{{ url('poem/'.$poem->id) }}" target="_blank">{{@$poem->title}}</a>
                                </h2>
                            </div>
                            <div class="poem-author">
                                <p>
                                    <a class="author_dynasty" href="{{ url('/poem/'.@$poem->dynasty) }}" target="_blank">{{@$poem->dynasty}}</a> : <a class="author_name" href="{{ url('/author/'.@$poem->author) }}" target="_blank">{{@$poem->author}}</a>
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
                            <div class="poem-tool clearfix">
                                <div class="collect" data-toggle="tooltip" data-placement="top" title="收藏" data-type="poem" data-id="{{$poem->id}}">
                                    <i class="fa fa-heart-o"></i>
                                </div>
                                <div class="copy" data-toggle="tooltip" data-placement="top" title="复制" data-type="poem" data-id="{{$poem->id}}">
                                    <i class="fa fa-clone"></i>
                                </div>
                                <div class="speaker" data-toggle="tooltip" data-placement="top" title="朗读" data-type="poem" data-id="{{$poem->id}}">
                                    <i class="fa fa-microphone" aria-hidden="true"></i>
                                </div>
                                <div class="like pull-right @if(isset($poem->status) && $poem->status == 'active') active @endif" data-toggle="tooltip" data-placement="top" title="喜欢" data-type="poem" data-id="{{$poem->id}}">
                                    <i class="fa fa-thumbs-o-up"></i> <span class="like_count">{{@$poem->like_count}}</span>
                                </div>
                            </div>
                            <div class="tool-qrcode">

                            </div>
                            <div class="poem-tag">
                                <p>
                                    @if(isset($poem->tags) && $poem->tags)
                                        @foreach(explode(',',$poem->tags) as $key=>$tag)
                                            @if($key+1 < count(explode(',',$poem->tags)))<a href="{{ url('poem?tag='.$tag) }}" class="tag">{{@$tag}} ,</a>@else<a href="" class="tag">{{@$tag}}</a>@endif
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