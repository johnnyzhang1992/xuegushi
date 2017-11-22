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
                                @foreach(json_decode($poem->tags) as $key=>$tag)
                                    @if($key+1 < count(json_decode($poem->tags)))<a href="{{ url('poem?tag='.$tag) }}" class="tag" target="_blank">{{@$tag}} ,</a>@else<a href="{{ url('poem?tag='.$tag) }}" class="tag" target="_blank">{{@$tag}}</a>@endif
                                @endforeach
                            @endif
                        </p>
                    </div>
                </div>
                @if(isset($detail))
                    {{--<section>--}}
                    {{--<p><strong>正文</strong></p>--}}
                    {{--@if(isset($detail->content) && count(json_decode($detail->content))>0)--}}
                    {{--@if(isset(json_decode($detail->content)->xu) && json_decode($detail->content)->xu)--}}
                    {{--<p style="color:#999">{{ @json_decode($detail->content)->xu}}</p>--}}
                    {{--@endif--}}
                    {{--@if(isset(json_decode($detail->content)->content) && json_decode($detail->content)->content)--}}
                    {{--@foreach(json_decode($detail->content)->content as $item)--}}
                    {{--<p>{{ @$item }}</p>--}}
                    {{--@endforeach--}}
                    {{--@endif--}}
                    {{--@endif--}}
                    {{--</section>--}}
                    @if(isset(json_decode($detail->yi)->content) && json_decode($detail->yi)->content)
                        <div class="poem-card">
                            <div class="card-title">
                                <h2 class="title">翻译</h2>
                            </div>
                            <div class="card-content">
                                @foreach(json_decode($detail->yi)->content as $item)
                                    <p>{{@$item}}</p>
                                @endforeach
                            </div>
                            <div class="reference">
                                @if(isset(json_decode($detail->yi)->reference) && json_decode($detail->yi)->reference)
                                    <h3 class="ref-title">{{@json_decode($detail->yi)->reference->title}}</h3>
                                    @if(is_array(json_decode($detail->yi)->reference->content))
                                        <ol>
                                            @foreach(json_decode($detail->yi)->reference->content as $item)
                                                <li>{{@$item}}</li>
                                            @endforeach
                                        </ol>
                                    @else
                                        <p> {{ @json_decode($detail->yi)->reference->content }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                    @if(isset(json_decode($detail->zhu)->content) && json_decode($detail->zhu)->content)
                        <div class="poem-card">
                            <div class="card-title">
                                <h2 class="title">注释</h2>
                            </div>
                            <div class="card-content">
                                <ol>
                                    @foreach(json_decode($detail->zhu)->content as $item)
                                        <li>{{@$item}}</li>
                                    @endforeach
                                </ol>
                            </div>
                            <div class="reference">
                                @if(isset(json_decode($detail->zhu)->reference) && json_decode($detail->zhu)->reference)
                                    <h3 class="ref-title">{{@json_decode($detail->zhu)->reference->title}}</h3>
                                    @if(is_array(json_decode($detail->zhu)->reference->content))
                                        <ol>
                                            @foreach(json_decode($detail->zhu)->reference->content as $item)
                                                <li>{{@$item}}</li>
                                            @endforeach
                                        </ol>
                                    @else
                                        <p> {{ @json_decode($detail->zhu)->reference->content }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                    @if(isset(json_decode($detail->shangxi)->content) && json_decode($detail->shangxi)->content)
                        <div class="poem-card">
                            <div class="card-title">
                                <h2 class="title">赏析</h2>
                            </div>
                            <div class="card-content">
                                @foreach(json_decode($detail->shangxi)->content as $item)
                                    <p style="text-indent: 30px">{!! @$item !!}</p>
                                @endforeach
                            </div>
                            <div class="reference">
                                @if(isset(json_decode($detail->shangxi)->reference) && json_decode($detail->shangxi)->reference)
                                    <h3 class="ref-title">{{@json_decode($detail->shangxi)->reference->title}}</h3>
                                    @if(is_array(json_decode($detail->shangxi)->reference->content))
                                        <ol>
                                            @foreach(json_decode($detail->shangxi)->reference->content as $item)
                                                <li>{{@$item}}</li>
                                            @endforeach
                                        </ol>
                                    @else
                                        <p> {{ @json_decode($detail->shangxi)->reference->content }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                    @if(isset($detail->more_infos) && isset(json_decode($detail->more_infos)->content))
                        <div class="poem-card">
                            <div class="card-title">
                                <h2 class="title">更多信息</h2>
                            </div>
                            <div class="card-content">
                                @if(isset(json_decode($detail->more_infos)->content) && json_decode($detail->more_infos)->content)
                                    @foreach(json_decode($detail->more_infos)->content as $item)
                                        <p>{{@$item}}</p>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
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