<?php
use App\Helpers\DateUtil;
?>
@section('review')
    <div class="PostComment">
        <div class="BlockTitle av-marginLeft av-borderColor PostComment-blockTitle">
            <span class="BlockTitle-title"><!-- react-text: 84 -->760 条评论<!-- /react-text --></span>
            <span class="BlockTitle-line"></span>
        </div>
        <div class="CommentEditor PostComment-mainEditor">
            @if (Auth::guest())
                <img class="Avatar-hemingway CommentEditor-avatar Avatar--xs" alt="小小梦工场" src="{{asset('/static/images/avatar.png')}}">
            @else
                <img class="Avatar-hemingway CommentEditor-avatar Avatar--xs" alt="{{asset(Auth::user()->name)}}" src="{{asset(Auth::user()->avatar)}}">
            @endif
            <div class="CommentEditor-input">
                <div class="Input-wrapper Input-wrapper--spread Input-wrapper--large Input-wrapper--noPadding">
                    <textarea class="richText form-control" name="postComment" id="comment" rows="2" placeholder="评论由作者筛选后显示"></textarea>
                </div>
                <div class="CommentEditor-actions" style="display: none">
                    <button class="Button Button--plain cancelReview" type="button">取消</button>
                    <button class="Button Button--blue saveReview" disabled="" type="button">评论</button>
                </div>
            </div>
        </div>
        <div class="PostCommentList">
            @include('zhuan.partials.comments')
        </div>
    </div>
@endsection

@section('review-modal')
    <div class="commentModal modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" style="display: none">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="commentModalLabel" style="text-align: center;font-size: 24px">查看对话</h4>
                </div>
                <div class="modal-body">
                    <div class="ConversationDialog">
                        <div>
                            <div class="ConversationDialog-list">
                                {{--对话内容--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('review-js')
    <script>
        $('.PostComment').on('focus','.richText',function(){
            var th = $(this);
            $(this).parent().parent().find('.CommentEditor-actions').show();
            if($(this).val() && $(this).val() !=''){
                $(th).parent().parent().find('.saveReview').removeAttr('disabled');
            }
        });
        $('.PostComment').on('input propertychange change','.richText',function () {
            var th = $(this);
            if($(this).val() && $(this).val() !=''){
                $(th).parent().parent().find('.saveReview').removeAttr('disabled');
            }else if(!$(this).val() || $(this).val() !=''){
                $(th).parent().parent().find('.saveReview').attr('disabled','')
            }
        });
        // 开头下的取消
        $('.PostComment-mainEditor').on('click','.cancelReview',function () {
            // $(this).parent().parent().find('textarea[name="postComment"]').val('');
            $(this).parent().find('.saveReview').attr('disabled','');
            $(this).parent().parent().find('.CommentEditor-actions').hide();
        });
        // 回复下的取消
        $('.PostComment,.ConversationDialog-list').on('click','.cancel-review',function () {
            $(this).parent().parent().parent().remove();
        });
        // 点击回复
        $('.PostComment,.ConversationDialog-list').on('click','.CommentItem-actionReply',function () {
            if($(this).parent().parent().find('.CommentEditor').length>0){
                $(this).parent().parent().find('.CommentEditor').remove();
                return false;
            }
            var id = $(this).attr('data-id');
            @if (Auth::guest())
                    var _img_url = "{{asset('/static/images/avatar.png')}}";
            @else
                    var _img_url = "{{ asset(Auth::user()->avatar) }}";
            @endif
            $(this).parent().parent().append('  <div class="CommentEditor CommentItem-inlineReply">\n' +
                '                    <img class="Avatar-hemingway CommentEditor-avatar Avatar--xs" alt="小小梦工场" src="'+_img_url+'">\n' +
                '                    <div class="CommentEditor-input">\n' +
                '                        <div class="Input-wrapper Input-wrapper--spread Input-wrapper--large Input-wrapper--noPadding">\n' +
                '                            <textarea class="richText form-control" name="postComment" id="comment" rows="2" placeholder="评论由作者筛选后显示"></textarea>\n' +
                '                        </div>\n' +
                '                        <div class="CommentEditor-actions">\n' +
                '                            <button class="Button Button--plain cancel-review" type="button">取消</button>\n' +
                '                            <button class="Button Button--blue saveReview" data-id="'+id+'" disabled="" type="button">评论</button>\n' +
                '                        </div>\n' +
                '                    </div>\n' +
                '                </div>')
        });
        // 提交
        $('.PostComment,.ConversationDialog-list').on('click','.saveReview',function () {
            var th = $(this);
            var _id = $(this).attr('data-id');
            var content = $(this).parent().parent().find('.richText').val();
            if(content && $.trim(content)!=''){
                $.post(
                    '/api/posts/{{@$post->id}}/comments',
                    {
                        '_token': $('input[name="_token"]').val(),
                        'content': content,
                        'parent_id':_id
                    },
                    function (res) {
                        if(res && res.status == 'success'){
                            $('body').toast({
                                position:'fixed',
                                content:res.msg,
                                duration:1000,
                                isCenter:true,
                                background:'rgba(51,122,183,0.8)',
                                animateIn:'bounceIn-hastrans',
                                animateOut:'bounceOut-hastrans'
                            });
                            $(th).parent().parent().find('.richText').val('');
                            getComments('/api/posts/{{@$post->id}}/comments');
                        }else{
                            $('body').toast({
                                position:'fixed',
                                content:res.msg,
                                duration:1000,
                                isCenter:true,
                                background:'rgba(0,0,0,0.5)',
                                animateIn:'bounceIn-hastrans',
                                animateOut:'bounceOut-hastrans'
                            });
                        }
                    }
                )
            }else{
                $('body').toast({
                    position:'fixed',
                    content:'评论内容不能为空哦',
                    duration:1000,
                    isCenter:true,
                    background:'rgba(0,0,0,0.5)',
                    animateIn:'bounceIn-hastrans',
                    animateOut:'bounceOut-hastrans'
                });
            }
        });
        // 查看对话
        $('.PostCommentList').on('click','.CommentItem-conversationButton',function () {
            var _id = $(this).attr('data-id');
            var _link = '/api/posts/'+'{{@$post->id}}'+'/comments/'+_id+'/conversation';
            var form_data1 = {
                _token:"{{ csrf_token() }}"
            };
            $.ajax({
                url: _link,
                type: 'GET',
                dataType: "html",
                cache: false,
                data: form_data1,
                success: function(data){
                    $('.ConversationDialog-list').html(data);
                    $('#commentModal').modal('show')
                },
                error: function(){
                    console.log("获取评论信息失败");
                }
            });
            return false;
        });
        // 获取评论信息
        $('.PostCommentList').on('click','.pagination a',function () {
            var _link = $(this).attr('href');
            getComments(_link);
            return false;
        });
        // 删除评论
        $('.PostCommentList').on('click','.CommentItem-actionDelete',function () {
            var th = $(this);
            var _id = $(this).attr('data-id');
            var _link = '/api/posts/'+'{{@$post->id}}'+'/comments/'+_id+'/delete';
            var form_data1 = {
                _token:"{{ csrf_token() }}"
            };
            $.ajax({
                url: _link,
                type: 'GET',
                cache: false,
                data: form_data1,
                success: function(data){
                    console.log(data);
                    if(data && data.status == 'success'){
                        $('body').toast({
                            position:'fixed',
                            content:data.msg,
                            duration:1000,
                            isCenter:true,
                            background:'rgba(51,122,183,0.8)',
                            animateIn:'bounceIn-hastrans',
                            animateOut:'bounceOut-hastrans'
                        });
                        $(th).parent().parent().remove();
                    }else{
                        $('body').toast({
                            position:'fixed',
                            content:data.msg,
                            duration:1000,
                            isCenter:true,
                            background:'rgba(0,0,0,0.5)',
                            animateIn:'bounceIn-hastrans',
                            animateOut:'bounceOut-hastrans'
                        });
                    }
                },
                error: function(){
                    $('body').toast({
                        position:'fixed',
                        content:'删除失败',
                        duration:1000,
                        isCenter:true,
                        background:'rgba(0,0,0,0.5)',
                        animateIn:'bounceIn-hastrans',
                        animateOut:'bounceOut-hastrans'
                    });
                }
            });
            return false;
        });
        // 点赞
        $('.PostCommentList,.ConversationDialog-list').on('click','.CommentItem-actionLike',function () {
            var th = $(this);
            var _id = $(this).attr('data-id');
            var _link = '/api/posts/'+'{{@$post->id}}'+'/comments/'+_id+'/like';
            var form_data1 = {
                _token:"{{ csrf_token() }}"
            };
            $.ajax({
                url: _link,
                type: 'POST',
                cache: false,
                data: form_data1,
                success: function(res){
                    if(res && res.status){
                        $('body').toast({
                            position:'fixed',
                            content:res.msg,
                            duration:1000,
                            isCenter:true,
                            background:'rgba(51,122,183,0.8)',
                            animateIn:'bounceIn-hastrans',
                            animateOut:'bounceOut-hastrans'
                        });
                        $(th).parent().find('.likeCount').html(res.count);
                        if(res.status == 'active'){
                            $(th).find('.likeText').html('取消赞');
                        }else{
                            $(th).find('.likeText').html('赞');
                        }
                    }else{
                        $('body').toast({
                            position:'fixed',
                            content:res.msg,
                            duration:1000,
                            isCenter:true,
                            background:'rgba(0,0,0,0.5)',
                            animateIn:'bounceIn-hastrans',
                            animateOut:'bounceOut-hastrans'
                        });
                    }
                },
                error: function(){
                    console.log("获取评论信息失败");
                }

            });
        });
        function getComments(_link) {
            var form_data1 = {
                _token:"{{ csrf_token() }}"
            };
            $.ajax({
                url: _link,
                type: 'GET',
                dataType: "html",
                cache: false,
                data: form_data1,
                success: function(data){
                    $('.PostCommentList').html(data);
                },
                error: function(){
                    console.log("获取评论信息失败");
                }

            });
        }
    </script>
@endsection
