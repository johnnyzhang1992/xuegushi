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
            <a class="Button SideBar-navLink Button--plain" href="{{ url('likes') }}" type="button">
                <i class="fa fa-thumbs-o-up"></i>
                <span class="SideBar-navText">我的喜欢</span>
                <span class="SideBar-navNumber">{{isset($like_count) ? $like_count : 0}}</span>
            </a>
        </li>
    </ul>
</div>
{{--故事专栏--}}
<div class="side-card zhuanlan">
    <div class="side-title">
        <h2><span class="author">古诗专栏</span></h2>
    </div>
    <div class="side-content">
        <a href="https://zhuanlan.xuegushi.cn"><i class="fa fa-th"></i><span class="name">专栏・发现</span></a>
    </div>
</div>
{{--小程序部分--}}
<div class="xcx-image clearfix" style="margin-bottom: 15px;display: flex;background: #fff;padding: 5px 10px;">
    <div class="col-md-5 col-xs-5" style="padding-left: 0">
        <img src="https://xuegushi.cn/static/common/xcx.jpg" class="img-responsive" />
    </div>
    <div class="col-md-7 col-xs-7" style="padding-right: 0;align-self: center;">
        {{--            <img src="https://xuegushi.cn/static/common/wechat.jpg" class="img-responsive" />--}}
        <p>扫描二维码</p>
        <p>打开小程序</p>
    </div>
</div>
{{--古诗类型--}}
<div class="side-card">
    <div class="side-title">
        <h2><span class="dynasty">类型</span></h2>
    </div>
    <div class="side-content">
        <a class="" href="{{ url('gushi/tangshi') }}">唐诗三百首</a>
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
{{--<div class="side-card">--}}
{{--    <div class="side-title">--}}
{{--        <h2><span class="author">微博 <small>古诗文小助手</small></span></h2>--}}
{{--    </div>--}}
{{--    <div class="side-content">--}}
{{--        <wb:follow-button uid="3546279424" type="red_2" width="136" height="24" ></wb:follow-button>--}}
{{--    </div>--}}
{{--</div>--}}
{{-- 谷歌广告 --}}
{{-- <div class="side-card">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <ins class="adsbygoogle" style="display:block" data-ad-format="fluid" data-ad-layout-key="-fb+5w+4e-db+86"
        data-ad-client="ca-pub-5735352629335736" data-ad-slot="8964065462"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
</div> --}}
<div class="side-card count-card" style="width:100%;overflow-x:scroll">
   <!-- 广告位：侧边栏 -->
    <script>
    (function() {
        var s = "_" + Math.random().toString(36).slice(2);
        document.write('<div id="' + s + '"></div>');
        (window.slotbydup=window.slotbydup || []).push({
            id: '7568860',
            container: s,
            size: '300,100',
            display: 'inlay-fix'
        });
    })();
    </script>
    <script src="//dup.baidustatic.com/js/os.js"></script>
</div>