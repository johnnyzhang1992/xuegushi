<header id="navigation" class="navbar navbar-default">
    <div class="col-md-9">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{url('/')}}"><b>{{ LAConfigs::getByKey('sitename') }}</b></a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li @if(isset($query) && $query =='home') class="active" @endif>
                    <a href="{{url('/')}}" class="nav-item">推荐</a>
                </li>
                <li @if(isset($query) && $query =='poems') class="active" @endif>
                    <a href="{{url('/poem')}}" class="nav-item">诗文</a>
                </li>
                {{--<li @if(isset($query) && $query =='sentence') class="active" @endif>--}}
                    {{--<a href="{{url('/sentence')}}" class="nav-item">名句</a>--}}
                {{--</li>--}}
                <li @if(isset($query) && $query =='collect') class="active" @endif>
                    <a href="{{url('/collect')}}" class="nav-item">收藏</a>
                </li>
                {{--<li @if(isset($query) && $query =='fhl') class="active" @endif>--}}
                    {{--<a href="{{url('/fhl')}}" class="nav-item">飞花令</a>--}}
                {{--</li>--}}
                <li @if(isset($query) && $query =='authors') class="active" @endif>
                    <a href="{{url('/author')}}" class="nav-item">作者</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                <li><a href="{{ url('/login') }}">登录</a></li>
                <li><a href="{{ url('/register') }}">注册</a></li>
                @else
                    <li class="dropdown">
                        <a role="button" class="nav-user dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('static/images/avatar.jpg') }}" class="user-image" alt="User Image"/>{{--{{ Auth::user()->name }}--}}<span class="caret"></span>
                        </a>
                        <ul id="dropdown" class="dropdown-menu">
                            <li><a href="{{ url('people/'.Auth::user()->id) }}" class="btn btn-default btn-flat"><i class="fa fa-user"></i> 个人页面</a></li>
                            @if(Auth::user()->id == 1)
                                <li><a href="{{ url('admin/') }}" class="btn btn-default btn-flat"><i class="fa fa-tachometer"></i> 后台管理</a></li>
                            @endif
                            <li><a href="https://xuegushi.cn/logout" class="btn btn-default btn-flat"> <i class="fa fa-power-off"></i> 登出</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</header>