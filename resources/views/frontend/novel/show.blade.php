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
            <div class="nav-breadcrumb" style="margin-bottom: 15px">
                <ol class="breadcrumb" style="margin-bottom: 0;background-color: #fff">
                    <li><a href="{{ url('/novel') }}">小说</a></li>
                    <li class="active">{{@$novel->name}}</li>
                </ol>
            </div>
            {{--left--}}
            @if(isset($lists) && $lists)
                <ul>
                    @foreach($lists as $list)
                        <li><a href="{{url('novel/'.$novel->id.'/'.$list->id)}}">{{$list->title}}</a></li>
                    @endforeach
                </ul>
            @endif
            {{@$lists->links()}}
        </div>
    </main>
@endsection

@section('content-js')

@endsection