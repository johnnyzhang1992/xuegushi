@extends("la.layouts.app")

@section("contentheader_title", "Author")
@section("contentheader_description", @$author->author_name)
@section("section", "Author")
@section("sub_section", @$author->author_name)
@section("htmlheader_title", @$author->author_name)

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
                <li role="presentation" class="active"><a href="#author-tab" aria-controls="author-tabe" role="tab" data-toggle="tab">正文</a></li>
                <li role="presentation"><a href="#detail-tab" aria-controls="detail-tab" role="tab" data-toggle="tab">详情</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="author-tab">
                    <h3>{{@$author->author_name}}</h3>
                    <p>朝代：{{@$author->dynasty}}</p>
                    <section>
                        @if(isset($author->profile) && $author->profile)
                            <p><strong>诗人简介</strong></p>
                            <p>{!! @$author->profile !!}</p>
                        @endif
                    </section>
                </div>
                <div role="tabpanel" class="tab-pane" id="detail-tab">
                    @if(isset($more_info))
                        <section>
                            @if(isset($more_info) && count($more_info)>0)
                                @foreach($more_info as $info)
                                    {{--{{ print_r(@$info) }}--}}
                                    <h4><strong>{{@$info->title}}</strong></h4>
                                    <div style="padding-left: 10px">
                                        @if(isset($info->content) && count($info->content)>0)
                                            @foreach($info->content as $item)
                                                <p>{!! str_replace('   ','<br>',$item) !!}</p>
                                            @endforeach
                                        @endif

                                        @if(isset($info->reference))
                                            <h5>{{@$info->reference->title}}</h5>
                                            @if($info->reference->content)
                                                @foreach($info->reference->content as $con)
                                                    <p>{!! @$con !!}</p>
                                                @endforeach
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </section>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    .tab-content > .tab-pane {
        padding: 0 20px;
    }
    p{
        text-align: justify;
    }
</style>
@endpush