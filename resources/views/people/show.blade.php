@extends('frontend.layout.people')

@section('content-css')
    <style>
        .main_content {
            background-color: #fff;
        }
        .footer{
            padding: 0 15px;
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
                个人首页
                访问IP：{{@$ip}}
            </div>
            {{--right--}}

            <div class="main_right col-md-4">
                <div class="side-card count-card">
                    <ul class="side-list">
                        <li class="list-item">
                            <a class="Button SideBar-navLink Button--plain" href="{{ url('collections') }}" target="_blank" type="button">
                                <i class="fa fa-star-o"></i>
                                <span class="SideBar-navText">我的收藏</span>
                                <span class="SideBar-navNumber">{{@$collect_count}}</span>
                            </a>
                        </li>
                        <li class="list-item">
                            <a class="Button SideBar-navLink Button--plain" href="#" target="_blank" type="button" >
                                <i class="fa fa-thumbs-o-up"></i>
                                <span class="SideBar-navText">我的喜欢</span>
                                <span class="SideBar-navNumber">{{@$like_count}}</span>
                            </a>
                        </li>
                    </ul>
                </div>
                @include('frontend.partials.footer')
            </div>
        </div>
    </main>
@endsection

@section('content-js')

@endsection