@extends('frontend.layout.poem')

@section('content-css')
    <style>
        .main_content{
            background-color: #fff;
        }
        .ContactPage{
            padding-bottom: 2em;
            min-height: 340px;
        }
        .ContactPage-title{
            font-weight: 700;
            font-size: 18px;
            outline: 0;
            margin: 0;
            padding-top: 15px;
            text-align: center;
            margin-bottom: 20px;
        }
        .ContactPage-para {
            margin-top: 2em;
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
            <div class="ContactPage col-md-12">
                <h1 class="ContactPage-title">{{@$page->display_name}}</h1>
                <div>
                    {!! @$page->html_content !!}
                </div>
            </div>
            @include('frontend.partials.page_footer')
        </div>
    </main>
@endsection

@section('content-js')

@endsection