<!DOCTYPE html>
<html>
<head>
    <title>没有权限.</title>

    {{--<link href="https://fonts.googleapis.com/css?family=Roboto:200,400" rel="stylesheet" type="text/css">--}}
    <link href="{{ asset('la-assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            display: table;
            font-weight: 200;
            color: #222;
            font: 16px/1.7 'Helvetica Neue', Helvetica, Arial, Sans-serif;
            background: #eff2f5;
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }
        .content h1{
            text-align: left;
            margin: 0;
        }
        .content .error{
            padding: 2em 1.25em;
            border: 1px solid #babbbc;
            border-radius: 5px;
            background: #f7f7f7;
        }
        .title {
            font-size: 16px;
            margin-bottom: 25px;
            color: #444;
        }
        a {
            font-weight:normal;
            color:#3061B6;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <h1 class="header">
            <a href="{{ url('/') }}" class="logo">
                古诗文小助手
            </a>
            - <small>403</small>
        </h1>
        <div class="error">
            {{--<i class="fa fa-search" style="font-size:120px;color:#FF5959;margin-bottom:30px;"></i>--}}
            <div class="title">你没有权限操作此页面哦！</div>
            <hr>
            <a href="{{ url('/') }}">返回首页</a> 或者
            <a href="javascript:history.back()">返回上页</a>
        </div>
    </div>
</div>
</body>
</html>
