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
<div class="side-card">
    <div class="side-title">
        <h2><span class="dynasty">朝代</span></h2>
    </div>
    <div class="side-content">
        <a class="" href="{{ url('poem?dynasty=先秦') }}" target="_blank">先秦</a>
        <a class="" href="{{ url('poem?dynasty=两汉') }}" target="_blank">两汉</a>
        <a class="" href="{{ url('poem?dynasty=魏晋') }}" target="_blank">魏晋</a>
        <a class="" href="{{ url('poem?dynasty=南北朝') }}" target="_blank">南北朝</a>
        <a class="" href="{{ url('poem?dynasty=隋代') }}" target="_blank">隋代</a>
        <a class="" href="{{ url('poem?dynasty=唐代') }}" target="_blank">唐代</a>
        <a class="" href="{{ url('poem?dynasty=五代') }}" target="_blank">五代</a>
        <a class="" href="{{ url('poem?dynasty=宋代') }}" target="_blank">宋代</a>
        <a class="" href="{{ url('poem?dynasty=金朝') }}" target="_blank">金朝</a>
        <a class="" href="{{ url('poem?dynasty=元代') }}" target="_blank">元代</a>
        <a class="" href="{{ url('poem?dynasty=明代') }}" target="_blank">明代</a>
        <a class="" href="{{ url('poem?dynasty=清代') }}" target="_blank">清代</a>
        <a class="" href="{{ url('poem?dynasty=近代') }}" target="_blank">近代</a>
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