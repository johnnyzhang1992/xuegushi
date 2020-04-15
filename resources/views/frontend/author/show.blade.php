@extends('frontend.layout.author')

@section('content-css')
<style>
    .google_ads_mobile{
        background-color: #fff;
        margin-bottom: 10px;
    }
    @media(min-width:768px){
        .google_ads_mobile{
            display: none
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
                        <li><a href="{{ url('/author') }}">作者</a></li>
                        <li class="active">{{@$author->author_name}}</li>
                        @if (!Auth::guest() && Auth::user()->id == 1)
                            <li class="pull-right"><a href="{{ url('admin/authors/'.@$author->id.'/edit') }}">编辑</a></li>
                        @endif
                    </ol>
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
                @endif
                <div class="google_ads_mobile">
                    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <ins class="adsbygoogle"
                         style="display:block"
                         data-ad-format="fluid"
                         data-ad-layout-key="-fb+5w+4e-db+86"
                         data-ad-client="ca-pub-5735352629335736"
                         data-ad-slot="8964065462"></ins>
                    <script>
                         (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>
                <div class="side-card hidden-lg hidden-md hidden-sm hidden-xs">
                    <div class="side-title">
                        <h2>
                            <span class="author">代表作品 <small>(总数:{{ @$poems_count }})</small></span> <a href="{{url('author/'.@$author->id.'/poems')}}" class="pull-right" style="font-size: 15px;line-height: 22px;">查看全部</a>
                        </h2>
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
                @if(isset($author->more_infos) && count(json_decode($author->more_infos))>0)
                    @foreach(json_decode($author->more_infos) as $item)
                        <div class="poem-card">
                            <div class="card-title">
                                <h2 class="title">{!! @$item->title !!}</h2>
                            </div>
                            <div class="card-content">
                                @if(isset($item->content) && $item->content)
                                    @foreach($item->content as $key=>$item1)
                                        @if($key == 2)
                                            <a type="button" class="toggle-expand" title="点此查看原文详情" >查看详情</a>
                                            <p class="hide">{!! @str_replace('</strong>','</strong><br>',$item1) !!}</p>
                                        @elseif($key >2)
                                            <p class="hide">{!! @str_replace('</strong>','</strong><br>',$item1) !!}</p>
                                        @else
                                            <p>{!! @str_replace('</strong>','</strong><br>',$item1) !!}</p>
                                        @endif
                                    @endforeach
                                    <a type="button" class="toggle-up" title="点此查看原文详情" style="display: none">点击收起</a>
                                @endif
                            </div>
                            @if(isset($item->reference) && $item->reference)
                                <div class="reference">
                                    <h3 class="ref-title">{{@$item->reference->title}}</h3>
                                    @if(is_array($item->reference->content))
                                        <ol>
                                            @foreach($item->reference->content as $item2)
                                                <li>{{@$item2}}</li>
                                            @endforeach
                                        </ol>
                                    @else
                                        <p> {{ @$item->reference->content }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
            {{--right--}}

            <div class="main_right col-md-4">
                @if(isset($hot_poems) && $hot_poems)
                    <div class="side-card">
                        <div class="side-title">
                            <h2>
                                <span class="author">代表作品 <small>(总数:{{ @$poems_count }})</small></span> <a href="{{url('author/'.@$author->id.'/poems')}}" class="pull-right" style="font-size: 15px;line-height: 22px;">查看全部</a>
                            </h2>
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
    <script>
        $('.toggle-expand').on('click',function(){
            $(this).parent().find('.hide').removeClass('hide').addClass('show1');
            $(this).hide();
            $(this).parent().find('.toggle-up').show();
        });
        $('.toggle-up').on('click',function(){
            $(this).parent().find('.show1').removeClass('show1').addClass('hide');
            $(this).hide();
            $(this).parent().find('.toggle-expand').show();
        });
    </script>
@endsection