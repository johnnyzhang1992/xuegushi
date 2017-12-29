@extends('zhuan.layout.base')

{{--@section('baidutongji')--}}
    {{--@include('frontend.partials.baidutongji')--}}
{{--@endsection--}}

{{--@section('googletongji')--}}
    {{--@include('frontend.partials.googletongji')--}}
{{--@endsection--}}

@section('base-css')
    @include('frontend.partials.base_css')
@endsection

@section('header')
    {{--header--}}
    @include('zhuan.partials.header')
@endsection

@section('content')
    {{--main-content--}}
    <main class="main_content col-md-12 no-padding">
        <div class="content col-md-9">
            <div class="main_left">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            </div>
        </div>
    </main>
@endsection

@section('content-css')
    <style>
        .main_content{
            background-color: #fff;
        }
        .navbar-nav li.write a{
            font-weight: 500;
        }
        .nav-right{
            position: relative;
            height: 60px;
        }
        .nav-right a{
            padding: 15px 15px;
            font-size: 18px;
            line-height: 30px;
            display: block;
            color: #777;
        }
        .nav-right a>svg{
            position: absolute;
            top: 18px;
            left: 0;
        }
        .nav-right a>span{
            margin-left: 12px;
        }
        .nav-right a:hover,.nav-right a:focus{
            color: #5e5e5e;
            background-color: transparent;
        }
        @media(min-width:768px){
            .navbar-collapse.collapse{
                display: none!important;
            }
        }
        @media(max-width:768px){
            .navbar-nav>li>a {
                padding-top: 5px;
                padding-bottom: 5px;
            }
            .nav-right{
                display: none;
            }
            .nav > li {
                float: initial;
                text-align: center;
            }
        }
        .navbar-default .navbar-toggle {
            border: none;
        }
        .navbar-default .navbar-toggle:focus, .navbar-default .navbar-toggle:hover {
            background-color: #fff;
        }
    </style>
@endsection

@section('goTop')
    @include('frontend.partials.goTop')
@endsection

@section('base-js')
    @include('frontend.partials.base_js')
@endsection