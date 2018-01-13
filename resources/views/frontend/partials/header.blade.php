<header id="navigation" class="navbar navbar-default">
    <div class="col-md-9">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{url('/')}}"><b>{{ LAConfigs::getByKey('sitename') }}</b><small> beta</small></a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li @if(isset($query) && $query =='home') class="active" @endif>
                    <a href="{{url('/')}}" class="nav-item">推荐</a>
                </li>
                <li @if(isset($query) && $query =='poems') class="active" @endif>
                    <a href="{{url('/poem')}}" class="nav-item">诗文</a>
                </li>
                <li @if(isset($query) && $query =='sentence') class="active" @endif>
                    <a href="{{url('/sentence')}}" class="nav-item">名句</a>
                </li>
                <li @if(isset($query) && $query =='collect') class="active" @endif>
                    <a href="{{url('/collections')}}" class="nav-item">收藏</a>
                </li>
                {{--<li @if(isset($query) && $query =='fhl') class="active" @endif>--}}
                    {{--<a href="{{url('/fhl')}}" class="nav-item">飞花令</a>--}}
                {{--</li>--}}
                <li @if(isset($query) && $query =='authors') class="active" @endif>
                    <a href="{{url('/author')}}" class="nav-item">作者</a>
                </li>
                <li style="position: relative">
                    <div class="input-group" style="width:270px;max-width: 300px;line-height:34px;padding: 13px 10px ">
                        <input type="text" id="search-input" class="form-control" placeholder="关键字搜索 诗文、诗人">
                        <span class="input-group-btn" id="search-now">
                            <button class="btn btn-default" type="button" style="padding: 3px 6px 0 6px;max-height: 34px;">
                                <svg class="svgIcon-use" width="25" height="25" viewBox="0 0 25 25">
                                    <path d="M20.067 18.933l-4.157-4.157a6 6 0 1 0-.884.884l4.157 4.157a.624.624 0 1 0 .884-.884zM6.5 11c0-2.62 2.13-4.75 4.75-4.75S16 8.38 16 11s-2.13 4.75-4.75 4.75S6.5 13.62 6.5 11z">
                                    </path>
                                </svg>
                            </button>
                        </span>
                    </div>
                    <div class="search-box" id="box" style="z-index: 9999;position: absolute;"></div>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                <li><a href="{{ url('/login') }}">登录</a></li>
                <li><a href="{{ url('/register') }}">注册</a></li>
                @else
                    <li class="dropdown">
                        <a role="button" class="nav-user dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('static/images/avatar.png') }}" class="user-image" alt="User Image"/>{{--{{ Auth::user()->name }}--}}<span class="caret"></span>
                        </a>
                        <ul id="dropdown" class="dropdown-menu">
                            {{--<li><a href="{{ url('people/'.Auth::user()->id) }}" class="btn btn-default btn-flat"><i class="fa fa-user"></i> 个人页面</a></li>--}}
                            <li><a href="{{ url('collections') }}" class="btn btn-default btn-flat"><i class="fa fa-star-o"></i> 我的收藏</a></li>
                            <li><a href="{{ url('likes') }}" class="btn btn-default btn-flat"><i class="fa fa-thumbs-o-up"></i> 我的喜欢</a></li>
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