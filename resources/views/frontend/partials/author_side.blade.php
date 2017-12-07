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