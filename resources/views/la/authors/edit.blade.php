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
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#author-tab" aria-controls="author-tabe" role="tab" data-toggle="tab">正文</a></li>
                <li role="presentation"><a href="#detail-tab" aria-controls="detail-tab" role="tab" data-toggle="tab">编辑详情</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="author-tab">
                    <h3><label for="author"></label>作者：<input type="text" id="author" name="author[author]" value="{{@$author->author_name}}"></h3>
                    <p><label for="dynasty"></label>朝代：<input type="text" id="dynasty" name="author[dynasty]" value="{{@$author->dynasty}}"></p>
                    <section>
                        <p><strong><label for="profile"></label>诗人简介</strong></p>
                        <textarea name="author[profile]" id="profile" cols="100" rows="10">{!! @$author->profile  !!}</textarea>
                    </section>
                    <div class="row col-md-12">
                        <button id="save-author" class="btn btn-success">提交</button>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="detail-tab">
                    <section>
                        <div id="more_info_editor"></div>
                    </section>
                    <div class="row col-md-12">
                        <button id="save-author-detail" class="btn btn-success">详情提交</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<link href="{{ asset('lib/jsoneditor/dist/jsoneditor.min.css') }}" rel="stylesheet" type="text/css">
<style>
    section{
        min-height: 40px;
        margin-bottom: 15px;
    }
    div.jsoneditor,div.jsoneditor-tree {
        min-height: 220px;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('lib/jsoneditor/dist/jsoneditor.min.js') }}"></script>
<script>
    var more_editor = new JSONEditor(document.getElementById('more_info_editor'),{});
    @if(isset($author->more_infos) && $author->more_infos)
        more_editor.set({!! \json_encode(\json_decode($author->more_infos), JSON_PRETTY_PRINT) !!});
    @else
        more_editor.set([{'content':[],'reference':{'content':[],'title':'参考资料'},'title':''}]);
    @endif
    $('#save-author').on('click',function () {
        var _data = {
            'id': '{{@$author->id}}',
            'dynasty' : $('#dynasty').val(),
            'author_name' : $('#author').val(),
            'profile' : $('#profile').val(),
            "_token":"{{csrf_token()}}"
        };
        console.log(_data);
        $.ajax({
            type: 'POST',
            url:'/admin/authors/save?type1=normal',
            data:_data,
            success:function (data) {
                console.log('===success');
                console.log(data.msg);
                if(data.msg == 'success'){
                    alert('保存成功！');
                    document.location.reload();
                }
            },
            error:function (data) {
                if(data.msg == 'error'){
                    alert('保存失败！');
                }
            }
        })
    });
    $('#save-author-detail').on('click',function () {
        var _data = {
            'id':'{{@$author->id}}',
            'more_infos':more_editor.get(),
            "_token":"{{csrf_token()}}"
        };
        $.ajax({
            type: 'POST',
            url:'/admin/authors/save?type1=detail',
            data:_data,
            success:function (data) {
                console.log('===success');
                if(data.msg == 'success'){
                    alert('保存成功！');
                    document.location.reload();
                }
            },
            error:function (data) {
                if(data.msg == 'error'){
                    alert('保存失败！');
                }
            }
        })
    });

</script>
@endpush