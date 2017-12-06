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