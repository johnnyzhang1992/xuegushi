<div class="neibox">
    @if(isset($poems) && $poems && count($poems)>0)
        <div class="main">
            <div class="mleft"><span>诗文</span></div>
            <div class="mright">
                @foreach($poems as $poem)
                    <div class="adiv" id="adiv0">
                        <a  id="aa0" href="{{url('/poem/'.@$poem->id)}}">{{@$poem->title}}<span> - {{@$poem->author}}</span></a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    @if(isset($tags) && $tags && count($tags)>0)
            <div class="main">
                <div class="mleft"><span>类型</span></div>
                <div class="mright">
                    @foreach($tags as $tag)
                        <div class="adiv" id="bdiv0">
                            <a  id="ba0" href="{{url('poem?tag='.$tag)}}">{{$tag}}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @if(isset($authors) &&  $authors && count($authors)>0)
        <div class="main">
            <div class="mleft"><span>作者</span></div>
            <div class="mright">
                @foreach($authors as $author)
                    <div class="adiv" id="ddiv0">
                        <a id="da0" href="{{url('/author/'.$author->id)}}">{{$author->author_name}}</a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
        @if(isset($sentences) && $sentences && count($sentences)>0)
            <div class="main">
                <div class="mleft"><span>名句</span></div>
                <div class="mright">
                    @foreach($sentences as $sentence)
                        <div class="adiv" id="adiv0">
                            <a  id="ma0" href="{{url('/poem/'.@$sentence->id)}}">{{@$sentence->title}}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
</div>