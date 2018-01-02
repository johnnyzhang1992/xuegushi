@extends('zhuan.layout.zhuanlan')

@section('content-css')
    <style>
        body {
            font-family: -apple-system,BlinkMacSystemFont,Helvetica Neue,PingFang SC,Microsoft YaHei,Source Han Sans SC,Noto Sans CJK SC,WenQuanYi Micro Hei,sans-serif;
            text-rendering: optimizeLegibility;
            line-height: 1.2;
            color: #333;
        }
        .ColumnAbout {
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            text-align: center;
        }
        .ColumnAbout-avatar {
            width: 100px;
            height: 100px;
            margin-bottom: 22px;
            border-radius: 50%;
        }
        .ColumnAbout-name {
            font-size: 20px;
            line-height: 28px;
            margin-bottom: 12px;
        }
        .ColumnAbout-intro {
            margin-bottom: 18px;
        }
        .ColumnAbout-actions {
            position: relative;
            margin-bottom: 18px;
        }
        .ColumnAbout-followers {
            display: block;
            margin-bottom: 20px;
            font-size: 14px;
            color: gray;
        }
        .ColumnTopicList {
            margin-bottom: 48px;
            text-align: center;
        }
        .ColumnTopicList li {
            display: inline-block;
            margin: 8px;
        }
        a {
            color: inherit;
            text-decoration: none;
        }
        .TopicTag.is-active {
            border-color: transparent;
            background: rgba(0,0,0,.06);
            cursor: default;
        }
        .TopicTag {
            display: inline-block;
            height: 30px;
            line-height: 30px;
            font-size: 14px;
            padding: 0 15px;
            border-radius: 15px;
            border: 1px solid rgba(0,0,0,.1);
            color: gray;
        }
        .ColumnTopicList-showall {
            padding: 4px 10px;
            font-size: 14px;
            color: gray;
        }
        .Menu {
            position: relative;
            border: 0;
            -webkit-box-shadow: none;
            box-shadow: none;
        }
        .ColumnAbout-menu {
            float: left;
            /*position: absolute;*/
            /*right: -56px;*/
            /*top: 2px;*/
            /*padding: 0;*/
        }
        .ColumnAbout-menuButton {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border: 1px solid currentColor;
            border-radius: 50%;
            color: #b3b3b3;
            -webkit-transition: -webkit-transform .2s ease-out .2s;
            transition: -webkit-transform .2s ease-out .2s;
            transition: transform .2s ease-out .2s;
            transition: transform .2s ease-out .2s,-webkit-transform .2s ease-out .2s;
        }
    </style>
@endsection

@section('content')
    <main class="main_content col-md-12 no-padding">
        <div class="content col-md-9 col-xs-12">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="container-stream col-md-9 col-md-offset-1 col-xs-12">
                <div class="ColumnAbout">
                    <img src="{{ asset($data->avatar) }}" class="ColumnAbout-avatar" alt="">
                    <h1 class="ColumnAbout-name">{{@$data->alia_name}}</h1>
                    <p class="ColumnAbout-intro">{{@$data->about}}</p>
                    <div>
                        <div class="ColumnAbout-actions">
                            @if(isset($data->verified) && $data->verified)
                                <button class="Button ColumnFollowButton btn btn-success" type="button">关注专栏</button>
                            @else
                                <button class="Button ColumnFollowButton btn btn-success" type="button">审核中</button>
                            @endif
                            <div class="Menu ColumnAbout-menu">
                                <button class="MenuButton btn  MenuButton-listen-click ColumnAbout-menuButton " aria-label="更多操作" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down-circle" color="#384047" data-reactid="91"><circle cx="12" cy="12" r="10"></circle><polyline points="8 12 12 16 16 12"></polyline><line x1="12" y1="8" x2="12" y2="16"></line></svg></button>
                                <button class="btn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-circle" color="#384047" data-reactid="131"><circle cx="12" cy="12" r="10"></circle><polyline points="16 12 12 8 8 12"></polyline><line x1="12" y1="16" x2="12" y2="8"></line></svg></button>
                                <div class="Menu-dropdown" style="visibility: hidden;"></div>
                            </div>
                        </div>
                        <a class="ColumnAbout-followers" href="{{ url($data->name.'/followers') }}" target="_blank">41,543人关注</a>
                    </div>
                    <ul class="ColumnTopicList">
                        <li>
                            <a href="{{ url($data->name) }}"><span class="TopicTag is-active">全部<span class="TopicTag-count">61</span></span></a>
                        </li>
                        <li><a href="/keepitreal?topic=%E5%BF%83%E7%90%86%E5%AD%A6"><span class="TopicTag">心理学<span class="TopicTag-count">14</span></span></a></li>
                        <li><button class="ColumnTopicList-showall" type="button">更多</button></li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('content-js')

@endsection