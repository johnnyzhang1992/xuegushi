<div class="side-card count-card">
    <ul class="side-list">
        <li class="list-item">
            <a class="Button SideBar-navLink Button--plain" href="{{ url('collections') }}" type="button">
                <i class="fa fa-star-o"></i>
                <span class="SideBar-navText">我的收藏</span>
                <span class="SideBar-navNumber">{{isset($collect_count) ? $collect_count : 0}}</span>
            </a>
        </li>
        <li class="list-item">
            <a class="Button SideBar-navLink Button--plain" href="{{ url('likes') }}" type="button" >
                <i class="fa fa-thumbs-o-up"></i>
                <span class="SideBar-navText">我的喜欢</span>
                <span class="SideBar-navNumber">{{isset($like_count) ? $like_count : 0}}</span>
            </a>
        </li>
    </ul>
</div>
<div class="side-card zhuanlan">
    <div class="side-title">
        <h2><span class="author">古诗专栏</span></h2>
    </div>
    <div class="side-content">
        <a href="https://zhuanlan.xuegushi.cn"><i class="fa fa-th"></i><span class="name">专栏・发现</span></a>
    </div>
</div>
<div class="side-card">
    <div class="side-title">
        <h2><span class="dynasty">类型</span></h2>
    </div>
    <div class="side-content">
        <a class="" href="{{ url('gushi/tangshi') }}" >唐诗三百首</a>
        <a class="" href="{{ url('gushi/songcisanbai') }}">宋词三百首</a>
        <a class="" href="{{ url('gushi/sanbai') }}">古诗三百首</a>
        <a class="" href="{{ url('gushi/shijing') }}">诗经</a>
        <a class="" href="{{ url('gushi/chuci') }}">楚辞</a>
        <a class="" href="{{ url('gushi/yuefu') }}">乐府</a>
        <a class="" href="{{ url('gushi/xiaoxue') }}">小学古诗</a>
        <a class="" href="{{ url('gushi/chuzhong') }}">初中古诗</a>
        <a class="" href="{{ url('gushi/gaozhong') }}">高中古诗</a>
        <a class="" href="{{ url('gushi/xiaoxuewyw') }}">小学文言文</a>
        <a class="" href="{{ url('gushi/chuzhongwyw') }}">初中文言文</a>
        <a class="" href="{{ url('gushi/gaozhongwyw') }}">高中文言文</a>
        <a class="" href="{{ url('gushi/songci') }}">宋词精选</a>
        <a class="" href="{{ url('gushi/shijiu') }}">古诗十九</a>
    </div>
</div>
@if(isset($h_authors) && $h_authors)
    <div class="side-card">
        <div class="side-title">
            <h2><span class="author">热门作者</span></h2>
        </div>
        <div class="side-content">
            @foreach($h_authors as $h_au)
                <a class="" href="{{ url('author/'.$h_au->id) }}" target="_blank">{{ @$h_au->author_name }}</a>
            @endforeach
        </div>
    </div>
@endif
<div class="side-card">
    <div class="side-title">
        <h2><span class="author">微博 <small>古诗文小助手</small></span></h2>
    </div>
    <div class="side-content">
        <wb:follow-button uid="3546279424" type="red_2" width="136" height="24" ></wb:follow-button>
    </div>
</div>