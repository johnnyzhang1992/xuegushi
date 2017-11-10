@extends("la.layouts.app")

@section("contentheader_title", "Poem")
@section("contentheader_description", @$poem->title)
@section("section", "Poem")
@section("sub_section", @$poem->title)
@section("htmlheader_title", @$poem->title)

@section("headerElems")

@endsection

@section("main-content")

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="box box-success">
        <!--<div class="box-header"></div>-->
        <div class="box-body">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#poem-tab" aria-controls="poem-tabe" role="tab" data-toggle="tab">正文</a></li>
                <li role="presentation"><a href="#detail-tab" aria-controls="detail-tab" role="tab" data-toggle="tab">详情</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="poem-tab">
                    <h3>{{@$poem->title}}</h3>
                    <p><a href="#">Like  <span class="badge">{{@$detail->like_count ? @$detail->like_count : 0}}</span></a></p>
                    <p>类型：{{ @$detail->type ? @$detail->type : '未知' }}</p>
                    <p>{{@$poem->dynasty}} : {{@$poem->author}} </p>
                    @if(isset($poem->tags) && json_decode($poem->tags))
                        <p>标签:
                            @foreach(json_decode($poem->tags) as $tag)
                                <span class="label label-success">{{ $tag }}</span>
                            @endforeach
                        </p>
                    @endif
                    <section>
                        @if(isset($poem_content) && $poem_content)
                            @if(isset($poem_content->xu) && $poem_content->xu)
                                <p style="color:#999">{{ @$poem_content->xu }}</p>
                            @endif
                            @if(isset($poem_content->content) && $poem_content->content)
                                @foreach($poem_content->content as $item)
                                    <p>{{ @$item }}</p>
                                @endforeach
                            @endif
                        @endif
                        @if(isset($poem->background) && $poem->background)
                            <p><strong>创作背景</strong></p>
                            <p>{!! @$poem->background !!}</p>
                        @endif
                    </section>
                </div>
                <div role="tabpanel" class="tab-pane" id="detail-tab">
                    @if(isset($detail))
                        <section>
                            <p><strong>正文</strong></p>
                            @if(isset($detail->content) && count(json_decode($detail->content))>0)
                                @if(isset(json_decode($detail->content)->xu) && json_decode($detail->content)->xu)
                                    <p style="color:#999">{{ @json_decode($detail->content)->xu}}</p>
                                @endif
                                @if(isset(json_decode($detail->content)->content) && json_decode($detail->content)->content)
                                    @foreach(json_decode($detail->content)->content as $item)
                                        <p>{{ @$item }}</p>
                                    @endforeach
                                @endif
                            @endif
                        </section>
                        <section>
                            <p><strong>翻译</strong></p>
                            @if(isset(json_decode($detail->yi)->content) && json_decode($detail->yi)->content)
                                @foreach(json_decode($detail->yi)->content as $item)
                                    <p>{{@$item}}</p>
                                @endforeach
                            @endif
                            @if(isset(json_decode($detail->yi)->reference) && json_decode($detail->yi)->reference)
                                <p>{{@json_decode($detail->yi)->reference->title}}</p>
                                @if(is_array(json_decode($detail->yi)->reference->content))
                                    @foreach(json_decode($detail->yi)->reference->content as $item)
                                        <p>{{@$item}}</p>
                                    @endforeach
                                @else
                                    <p> {{ @json_decode($detail->yi)->reference->content }}</p>
                                @endif
                            @endif
                        </section>
                        <section>
                            <p><strong>注释</strong></p>
                            @if(isset(json_decode($detail->zhu)->content) && json_decode($detail->zhu)->content)
                                @foreach(json_decode($detail->zhu)->content as $item)
                                    <p>{{@$item}}</p>
                                @endforeach
                            @endif
                            @if(isset(json_decode($detail->zhu)->reference) && json_decode($detail->zhu)->reference)
                                <p>{{@json_decode($detail->zhu)->reference->title}}</p>
                                @if(is_array(json_decode($detail->zhu)->reference->content))
                                    @foreach(json_decode($detail->zhu)->reference->content as $item)
                                        <p>{{@$item}}</p>
                                    @endforeach
                                @else
                                    <p> {{ @json_decode($detail->zhu)->reference->content }}</p>
                                @endif
                            @endif
                        </section>
                        <section>
                            <p><strong>赏析</strong></p>
                            @if(isset(json_decode($detail->shangxi)->content) && json_decode($detail->shangxi)->content)
                                @foreach(json_decode($detail->shangxi)->content as $item)
                                    <p>{{@$item}}</p>
                                @endforeach
                            @endif
                            @if(isset(json_decode($detail->shangxi)->reference) && json_decode($detail->shangxi)->reference)
                                <p>{{@json_decode($detail->shangxi)->reference->title}}</p>
                                @if(is_array(json_decode($detail->shangxi)->reference->content))
                                    @foreach(json_decode($detail->shangxi)->reference->content as $item)
                                        <p>{{@$item}}</p>
                                    @endforeach
                                @else
                                    <p> {{ @json_decode($detail->shangxi)->reference->content }}</p>
                                @endif
                            @endif
                        </section>
                        <section>
                            <p><strong>更多信息</strong></p>
                            @if(isset($detail->more_infos) && $detail->more_infos)
                                @if(isset(json_decode($detail->more_infos)->content) && json_decode($detail->more_infos)->content)
                                    @foreach(json_decode($detail->more_infos)->content as $item)
                                        <p>{{@$item}}</p>
                                    @endforeach
                                @endif
                            @endif
                        </section>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')

@endpush

@push('scripts')
<script>

</script>
@endpush