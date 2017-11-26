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
                    <a role="button" class="topTypeHeader-rightItem pull-left">类型</a>
                    <div class="topTypeHeader-nav">
                        <a href="{{url('poem?type=诗')}}" class="topTypeHeader-navItem @if(isset($type) && $type =='诗') active @endif">诗</a>
                        <a href="{{url('poem?type=词')}}" class="topTypeHeader-navItem @if(isset($type) && $type =='词') active @endif">词</a>
                        <a href="{{url('poem?type=曲')}}" class="topTypeHeader-navItem @if(isset($type) && $type =='曲') active @endif">曲</a>
                        <a href="{{url('poem?type=文言文')}}" class="topTypeHeader-navItem @if(isset($type) && $type =='文言文') active @endif">文言文</a>
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
                                <div class="collect" data-toggle="tooltip" data-placement="top" title="收藏">
                                    <i class="fa fa-heart-o"></i>
                                </div>
                                <div class="copy" data-toggle="tooltip" data-placement="top" title="复制">
                                    <i class="fa fa-clone"></i>
                                </div>
                                <div class="speaker" data-toggle="tooltip" data-placement="top" title="朗读">
                                    <i class="fa fa-microphone" aria-hidden="true"></i>
                                </div>
                                <div class="like pull-right" data-toggle="tooltip" data-placement="top" title="喜欢">
                                    <i class="fa fa-thumbs-o-up"></i> {{@$poem->like_count}}
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