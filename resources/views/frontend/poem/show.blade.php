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
                <div class="nav-breadcrumb" style="margin-bottom: 15px">
                    <ol class="breadcrumb" style="margin-bottom: 0;background-color: #fff">
                        <li><a href="{{ url('/poem') }}">诗文</a></li>
                        <li class="active">{{@$poem->title}}</li>
                        @if (!Auth::guest() && Auth::user()->id == 1)
                        <li class="pull-right"><a href="{{ url('admin/poems/'.@$poem->id.'/edit') }}">编辑</a></li>
                        @endif
                    </ol>
                </div>
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
                            <i class="fa  @if(isset($poem->collect_status) && $poem->collect_status == 'active') fa-star @else fa-star-o @endif"></i>
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
                                @foreach(explode(',',$poem->tags) as $key=>$tag)
                                    @if($key+1 < count(explode(',',$poem->tags)))<a href="{{ url('poem?tag='.$tag) }}" class="tag" target="_blank">{{@$tag}} ,</a>@else<a href="{{ url('poem?tag='.$tag) }}" class="tag" target="_blank">{{@$tag}}</a>@endif
                                @endforeach
                            @endif
                        </p>
                    </div>
                </div>
                @if(isset($author) && $author)
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
                            @if(isset($poem->collect_a_status) && $poem->collect_a_status == 'active')
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
                @endif
                @if(isset($detail))
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
                            @if(isset(json_decode($detail->yi)->reference->content) && json_decode($detail->yi)->reference->content)
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
                            @endif
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
                            @if(isset(json_decode($detail->zhu)->reference->content) && json_decode($detail->zhu)->reference->content)
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
                            @endif
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
                            @if(isset(json_decode($detail->shangxi)->reference->content) && json_decode($detail->shangxi)->reference->content)
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
                            @endif
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
                @if(isset($hot_poems) && $hot_poems)
                    <div class="side-card">
                        <div class="side-title">
                            <h2><span class="author">代表作品 <small>(作品总数：{{ @$poems_count }})</small></span></h2>
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