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
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#poem-tab" aria-controls="poem-tabe" role="tab" data-toggle="tab">正文</a></li>
                <li role="presentation"><a href="#detail-tab" aria-controls="detail-tab" role="tab" data-toggle="tab">编辑详情</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="poem-tab">

                    <h3>标题：<input type="text" id="title" name="poem[title]" value="{{@$poem->title}}"></h3>
                    <div class="col-md-12" style="margin-bottom: 10px">
                        <a href="#">Like   <span class="badge">{{@$detail->like_count ? @$detail->like_count : 0}}</span></a>
                    </div>
                    <p>朝代：<input type="text" id="dynasty" name="poem[dynasty]" value="{{@$poem->dynasty}}"> :作者：<input type="text" id="author" name="poem[author]" value="{{@$poem->author}}"></p>
                    <section>
                        <div class="col-md-12 no-padding">
                            <div class="col-md-1 no-padding" style="max-width: 45px;line-height: 34px">标签：</div>
                            <div class="col-md-10 no-padding">
                                <input class="form-control" id="tags" type="text" name="poem[tags]" value="{{@$poem->tags}}">
                            </div>
                        </div>
                    </section>
                    <section>
                        <div class="col-md-12 no-padding clearfix">
                            <div class="col-md-1 no-padding"><label for="type">类型：</label></div>
                            <div class="col-md-3">
                                <select class="form-control" id="type" name="poem[type]">
                                    <option  value="诗" @if(isset($poem->type) && $poem->type == '诗') selected = "selected" @endif>诗</option>
                                    <option  value="词" @if(isset($poem->type) && $poem->type == '词') selected = "selected" @endif>词</option>
                                    <option  value="曲" @if(isset($poem->type) && $poem->type == '曲') selected = "selected" @endif>曲</option>
                                    <option  value="文言文" @if(isset($poem->type) && $poem->type == '文言文')selected = "selected" @endif>文言文</option>
                                </select>
                            </div>
                        </div>
                    </section>
                    <section>
                        <div id="content-jsoneditor"></div>
                    </section>
                    <section>
                        <p><strong><label for="background"></label>创作背景</strong></p>
                        <textarea name="poem[background]" id="background" cols="100" rows="10">{{@$poem->background}}</textarea>
                    </section>
                    <div class="row col-md-12">
                        <button id="save-poem" class="btn btn-success">提交</button>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="detail-tab">
                    <section>
                        <p><strong>翻译</strong></p>
                        <div id="yi-jsoneditor"></div>
                    </section>
                    <section>
                        <p><strong>注释</strong></p>
                        <div id="zhu-editor"></div>
                    </section>
                    <section>
                        <p><strong>赏析</strong></p>
                        <div id="shangxi-editor"></div>
                    </section>
                    <section>
                        <p><strong>更多信息</strong></p>
                        <div id="more_info_editor"></div>
                    </section>
                    <div class="row col-md-12">
                        <button id="save-poem-detail" class="btn btn-success">详情提交</button>
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
        overflow: hidden;
        clear: both;
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
    // create the editor
    var container = document.getElementById("content-jsoneditor");
    var content_editor = new JSONEditor(container, {});
    @if(isset($poem->content) && $poem->content)
         content_editor.set({!! \json_encode(\json_decode($poem->content), JSON_PRETTY_PRINT)  !!});
    @else
         content_editor.set("{'content':[]}");
    @endif
    // get json
    var json_con = content_editor.get();

    var yi_editor = new JSONEditor(document.getElementById('yi-jsoneditor'),{});
    @if(isset($detail->yi) && $detail->yi)
         yi_editor.set({!! \json_encode(\json_decode($detail->yi), JSON_PRETTY_PRINT) !!});
    @else
         yi_editor.set({'content':[],'reference':{'title':'参考资料','content':[]}});
    @endif

    var zhu_editor = new JSONEditor(document.getElementById('zhu-editor'),{});
    @if(isset($detail->zhu) && $detail->zhu)
        zhu_editor.set({!! \json_encode(\json_decode($detail->zhu), JSON_PRETTY_PRINT) !!});
    @else
        zhu_editor.set({'content':[],'reference':{'title':'参考资料','content':[]}});
    @endif

    var shang_editor = new JSONEditor(document.getElementById('shangxi-editor'),{});
    @if(isset($detail->shangxi) && $detail->shangxi)
        shang_editor.set({!! \json_encode(\json_decode($detail->shangxi), JSON_PRETTY_PRINT) !!});
    @else
        shang_editor.set({'content':[],'reference':{'title':'参考资料','content':[]}});
    @endif

    var more_editor = new JSONEditor(document.getElementById('more_info_editor'),{});
    @if(isset($detail->more_infos) && $detail->more_infos)
        more_editor.set({!! \json_encode(\json_decode($detail->more_infos), JSON_PRETTY_PRINT) !!});
    @else
        more_editor.set({'content':[]});
    @endif
    $('#save-poem').on('click',function () {
        var _data = {
            'id': '{{@$poem->id}}',
            'title' : $('#title').val(),
            'dynasty' : $('#dynasty').val(),
            'author' : $('#author').val(),
            'type' : $('#type').val(),
            'tags' : $('#tags').val(),
            'background' : $('#background').val(),
            'con': content_editor.get(),
            "_token":"{{csrf_token()}}"
        };
        console.log(_data);
        $.ajax({
            type: 'POST',
            url:'/admin/poem/save?type1=normal',
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
    $('#save-poem-detail').on('click',function () {
        var _data = {
            'id':'{{@$detail->id}}',
            'yi': yi_editor.get(),
            'zhu': zhu_editor.get(),
            'shang': shang_editor.get(),
            'more_info':more_editor.get(),
            "_token":"{{csrf_token()}}"
        };
        $.ajax({
            type: 'POST',
            url:'/admin/poem/save?type1=detail',
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