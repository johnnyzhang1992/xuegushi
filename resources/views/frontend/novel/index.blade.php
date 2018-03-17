@extends('frontend.layout.poem')

@section('content-css')
    <style>
        .content{
            padding: 20px 15px;
        }
        ul{
            padding-left: 0;
        }
        li{
            padding: 5px 0;
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
            @if(isset($lists) && $lists)
                <ul>
                    @foreach($lists as $list)
                        <li><a href="{{url('novel/'.$list->id)}}">{{$list->name}} </a><small>{{$list->author}}</small></li>
                    @endforeach
                </ul>
            @endif
        </div>
    </main>
@endsection

@section('content-js')

@endsection