<header id="navigation" class="navbar navbar-default">
    <div class="col-md-9 col-xs-12">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal" color="#999" data-reactid="801"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
            </button>
            <a class="navbar-brand" href="{{url('/')}}"><b>古诗专栏 <small>beta</small></b></a>
        </div>
        @yield('top-menu')
        {{--<ul class="nav navbar-nav nav-left">--}}
            {{--<li @if(isset($query) && $query =='home') class="active" @endif>--}}
                {{--<a href="{{url('/')}}" class="nav-item">首页</a>--}}
            {{--</li>--}}
            {{--<li @if(isset($query) && $query =='explore') class="active" @endif>--}}
                {{--<a href="{{url('/explore')}}" class="nav-item">发现</a>--}}
            {{--</li>--}}
        {{--</ul>--}}
        <div class="nav-right pull-right">
            @if(isset($is_has) && $is_has)
            @else
                <a href="{{ url('/apply') }}" class="apply"><span>申请专栏</span></a>
            @endif
            <a href="{{ url('/write') }}" class="blue-color write"><span>写文章</span></a>
            @if (Auth::guest())
                <a href="{{url('/login')}}" class="login">登录</a>
            @else
                <a role="button" class="avatar nav-user dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('static/images/avatar.png') }}" class="user-image" alt="User Image"/>
                </a>
            @endif
            @if (!Auth::guest())
            <ul id="dropdown" class="dropdown-menu">
                <li>
                    <a href="{{ url('people/'.Auth::user()->id) }}" class="btn btn-default btn-flat"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home" color="#999" data-reactid="631"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg><span>我的主页</span></a>
                </li>
                <li>
                    <a href="{{ url('me/publications') }}" class="btn btn-default btn-flat"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book" color="#999" data-reactid="206"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg><span>我的专栏</span></a>
                </li>
                <li>
                    <a href="{{ url('me/subscribes') }}" class="btn btn-default btn-flat"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag" color="#999" data-reactid="1031"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg><span>我的订阅</span></a>
                </li>
                <li role="separator" class="divider"></li>
                <li class="write">
                    <a href="{{ url('me/posts') }}"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard" color="#999" data-reactid="311"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg> <span>我的文章</span></a>
                </li>
                <li class="write">
                    <a href="{{ url('me/drafts') }}"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash" color="#999" data-reactid="1166"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg><span>我的草稿</span></a>
                </li>
                <li class="write">
                    <a href="{{ url('me/collections') }}"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark" color="#999" data-reactid="211"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg><span>我的收藏</span></a>
                </li>
                <li role="separator" class="divider"></li>
                @if(Auth::user()->id == 1)
                    <li>
                        <a href="https://xuegushi.cn/admin" class="btn btn-default btn-flat"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-compass" color="#999" data-reactid="366"><circle cx="12" cy="12" r="10"></circle><polygon points="16.22 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon></svg> <span>后台管理</span></a>
                    </li>
                @endif
                <li>
                    <a href="{{ url('me/setting') }}" class="btn btn-default btn-flat"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings" color="#999" data-reactid="1006"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg> <span></span>我的设置</a>
                </li>
                <li>
                    <a href="{{ url('/logout') }}" class="btn btn-default btn-flat"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out" color="#999" data-reactid="711"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span>登出</span></a>
                </li>
            </ul>
            @endif
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">登录</a></li>
                @else
                    <li class="write"><a href="{{url('/people/'.Auth::user()->id)}}">我的主页</a></li>
                    @if(isset($is_has) && $is_has)
                    @else
                        <li class="write"><a href="{{ url('/write') }}"><span>申请专栏</span></a></li>
                    @endif
                    <li class="write"><a href="{{ url('/write') }}"><span>写文章</span></a></li>
                    <li class="write"><a href="">我的文章</a></li>
                @endif
                    <li class="write"><a href="">专栏 · 发现</a></li>
            </ul>
        </div>
    </div>
</header>