<header id="navigation" class="navbar navbar-default">
    <div class="col-md-9 col-xs-12">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal" color="#384047" data-reactid="801"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
            </button>
            <a class="navbar-brand" href="{{url('/')}}"><b>古诗专栏</b></a>
        </div>
        <div class="nav-right pull-right">
            <a href="{{ url('/write') }}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3" color="#777"><polygon points="14 2 18 6 7 17 3 17 3 13 14 2"></polygon><line x1="3" y1="22" x2="21" y2="22"></line></svg> <span>写文章</span></a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">登录</a></li>
                @else
                    <li class="write"><a href="{{ url('/write') }}"><span>写文章</span></a>
                    </li>
                    <li class="write"><a href="">我的文章</a></li>
                    {{--<li class="dropdown">--}}
                        {{--<a role="button" class="nav-user dropdown-toggle" data-toggle="dropdown" aria-expanded="false">--}}
                            {{--<img src="{{ asset('static/images/avatar.png') }}" class="user-image" alt="User Image"/>--}}{{--{{ Auth::user()->name }}--}}{{--<span class="caret"></span>--}}
                        {{--</a>--}}
                        {{--<ul id="dropdown" class="dropdown-menu">--}}
                            {{--<li><a href="{{ url('people/'.Auth::user()->id) }}" class="btn btn-default btn-flat"><i class="fa fa-user"></i> 个人页面</a></li>--}}
                            {{--@if(Auth::user()->id == 1)--}}
                                {{--<li><a href="{{ url('admin/') }}" class="btn btn-default btn-flat"><i class="fa fa-tachometer"></i> 后台管理</a></li>--}}
                            {{--@endif--}}
                            {{--<li><a href="https://xuegushi.cn/logout" class="btn btn-default btn-flat"> <i class="fa fa-power-off"></i> 登出</a></li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                @endif
                <li class="write"><a href="">专栏 · 发现</a></li>
            </ul>
        </div>
    </div>
</header>