@extends('frontend.layout.poem')

@section('content-css')
    <style>
        h3.top-nav{
            margin-top: 10px;
            padding-left: 10px;
            margin-left: 15px;
            border-left: 4px solid #0d6aad ;
        }
        div.item.col-md-4{
            padding-left: 0;
            margin-bottom: 10px;
            overflow: hidden;
            height: 20px;
            text-overflow: ellipsis;
        }
        div.item.col-md-4:before{
            content: '•';;
        }
        .main_left{
            padding-left: 0;
            padding-right: 0;
        }
        .gushiContent{
            margin-top: 20px;
            background-color: #fff;
            padding: 15px 0 15px 10px;
        }
        .book-item {
            border-bottom: 1px solid #e6e6e6;
        }
        .book-item h3{
            margin-bottom: 20px;
            font-size: 20px;
        }
        .book-item:first-child h3{
            margin-top: 10px;
        }
        .book-item:last-child{
            border-bottom: none;
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
                    <h3 class="top-nav">{{@$title}}</h3>
                </div>
                <div class="gushiContent clearfix">
                    @if(isset($data) && $data)
                        @if(isset($books) && $books)
                            @foreach($books as $key=>$book)
                                <div class="book-item clearfix">
                                    <h3>{{@$book}}</h3>
                                    @foreach($data as $item)
                                        @if($book == $item->book)
                                            <div class="col-md-4 col-xs-6 item"  data-toggle="tooltip" data-placement="left" title="{{@$item->title}}({{@$item->author}})">
                                                <a href="{{url('poem/'.@$item->id)}}" title="{{@$item->title}}({{@$item->author}})">{{@$item->title}}</a><small>({{@$item->author}})</small>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach
                        @else
                            @foreach($data as $item)
                                <div class="col-md-4 col-xs-6 item"  data-toggle="tooltip" data-placement="left" title="{{@$item->title}}({{@$item->author}})">
                                    <a href="{{url('poem/'.@$item->id)}}" title="{{@$item->title}}({{@$item->author}})">{{@$item->title}}</a><small>({{@$item->author}})</small>
                                </div>
                            @endforeach
                        @endif
                    @endif
                </div>
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