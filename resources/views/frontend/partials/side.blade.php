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
<div class="side-card">
    <div class="side-title">
        <h2><span class="author">热门作者</span></h2>
    </div>
    <div class="side-content">
        @if(isset($h_authors) && $h_authors)
            @foreach($h_authors as $h_au)
                <a class="" href="{{ url('author/'.$h_au->id) }}" target="_blank">{{ @$h_au->author_name }}</a>
            @endforeach
        @else
            <a class="" href="{{ url('author/185') }}" target="_blank">李白</a>
            <a class="" href="{{ url('author/187') }}" target="_blank">杜甫</a>
            <a class="" href="{{ url('author/1814') }}" target="_blank">苏轼</a>
        @endif
    </div>
</div>
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
            <a class="Button SideBar-navLink Button--plain" href="{{ url('likes') }}" target="_blank" type="button" >
                <i class="fa fa-thumbs-o-up"></i>
                <span class="SideBar-navText">我的喜欢</span>
                <span class="SideBar-navNumber">{{@$like_count}}</span>
            </a>
        </li>
    </ul>
</div>