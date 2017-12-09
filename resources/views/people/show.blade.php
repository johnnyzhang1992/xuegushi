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
                <p>个人页面正在开发中。。。</p>
                <p>用户设置页面也正在开发中。。。</p>
                <p>默认头像是。。允儿小姐姐（漂亮不</p>
                <p>不要着急。。你要是急就是一起来敲代码呀(:-</p>
                <p>访问IP：{{@$ip}}</p>
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