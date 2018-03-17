@extends('frontend.layout.poem')

@section('content-css')
    <style>
        .content{
            padding: 20px 15px;
        }
        ul{
            padding-left: 0;
        }
        .novel-content{
            margin-top: -45px;
            text-align: justify;
            line-height: 25px;
            font-size: 18px;
            color: #666;
        }
        .next-chapter{
            margin-top: 20px;
            text-align: center;
            background-color: #fff;
            padding: 10px 15px;
            margin-left: -15px;
            margin-right: -15px;
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
            <div class="nav-breadcrumb" style="margin-bottom: 15px">
                <ol class="breadcrumb" style="margin-bottom: 0;background-color: #fff">
                    <li><a href="{{ url('/novel') }}">小说</a></li>
                    <li><a href="{{ url('/novel/'.$detail->book_id)}}">{{@$detail->book_name}}</a> </li>
                    {{--<li class="active">{{@$detail->title}}</li>--}}
                </ol>
            </div>
            <div class="nav-breadcrumb" style="margin-bottom: 15px">
                <ol class="breadcrumb" style="margin-bottom: 0;background-color: #fff">
                    <li class="active">{{@$detail->title}}</li>
                </ol>
            </div>
            <div class="novel-content">
                @if($detail->book_id == 1)
                    {!! str_replace('    ','<br><br>',@$detail->content) !!}
                @else
                    {!! str_replace('&nbsp;&nbsp;','<br>',htmlentities(trim(@$detail->content))) !!}
                @endif

            </div>
            <div class="next-chapter">
                <a href="{{ url('/novel/'.$detail->book_id.'/'.($detail->id-1)) }}" class="btn btn-info">上一章</a>
                <a href="{{ url('/novel/'.$detail->book_id.'/'.($detail->id+1)) }}" class="btn btn-info">下一章</a>
            </div>
        </div>
    </main>
@endsection

@section('content-js')

@endsection