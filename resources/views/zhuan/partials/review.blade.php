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
                <img class="Avatar-hemingway CommentEditor-avatar Avatar--xs" alt="小小梦工场" src="{{asset(Auth::user()->avatar)}}">
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
            @if(isset($comments) && $comments)
                @foreach($comments as $key=>$comment)
                    <div class="CommentItem">
                        <a class="UserAvatar CommentItem-author" href="{{url($comment->domain ? 'people/'.$comment->domain : 'people/'.$comment->u_id)}}" target="_blank"><img class="Avatar-hemingway Avatar--xs" alt="{{@$comment->name}}" src="{{asset(@$comment->avatar)}}"></a>
                        <div class="CommentItem-headWrapper">
                            @if(isset($comment->parent_id) && $comment->parent_id>0)
                                <button class="Button CommentItem-conversationButton Button--plain" type="button" data-toggle="modal" data-target="#commentModal">
                                    <i class="fa fa-comments"></i> 查看对话
                                </button>
                            @endif
                            <div class="CommentItem-head">
                                <span class="CommentItem-context">
                                    <a href="{{url($comment->domain ? 'people/'.$comment->domain : 'people/'.$comment->u_id)}}" class="" target="_blank">{{@$comment->name}}</a>
                                    @if(isset($comment->parent_id) && $comment->parent_id>0)
                                        <span class="CommentItem-replyTo">
                                        <span class="CommentItem-replySplit">回复</span>
                                        <a href="{{url($comment->p_domain ? 'people/'.$comment->p_domain : 'people/'.$comment->p_u_id)}}" class="" target="_blank">{{@$comment->p_name}}</a>
                                    </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="CommentItem-content">{{@$comment->content}}</div>
                        <div class="CommentItem-foot">
                            <span class="CommentItem-like" title="382 人觉得这个很赞">{{@$comment->like_count}} 赞</span>
                            <div class="HoverTitle CommentItem-createdTime" data-hover-title="2017 年 11月 27 日星期一晚上 11 点 50 分">
                                <time datetime="Mon Nov 27 2017 23:50:54 GMT+0800 (中国标准时间)">{{@ DateUtil::formatDate(strtotime($comment->created_at))}}</time>
                            </div>
                            <button class="Button CommentItem-action CommentItem-actionReply Button--plain" type="button" data-id="{{@$comment->id}}"><i class="fa fa-reply"></i>回复</button>
                            <button class="Button CommentItem-action CommentItem-actionLike Button--plain" type="button"><i class="fa fa-thumbs-o-up"></i>赞</button>
                        </div>
                    </div>
                    @if($key == 3 && $comment->currentPage()==1)
                        {{--这部分只有第一页存在--}}
                        <div class="BlockTitle av-marginLeft av-borderColor PostComment-split">
                            <span class="BlockTitle-title">以上为精选评论</span>
                            <span class="BlockTitle-line"></span>
                        </div>
                    @endif
                @endforeach
            @endif
            {{@$comments->links()}}
            {{--分页--}}
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
                                <div class="CommentItem">
                                    <a class="UserAvatar CommentItem-author" href="https://www.zhihu.com/people/cheneyfm" target="_blank">
                                        @if (Auth::guest())
                                            <img class="Avatar-hemingway CommentEditor-avatar Avatar--xs" alt="小小梦工场" src="{{asset('/static/images/avatar.png')}}">
                                        @else
                                            <img class="Avatar-hemingway CommentEditor-avatar Avatar--xs" alt="小小梦工场" src="{{asset(Auth::user()->avatar)}}">
                                        @endif
                                    </a>
                                    <div class="CommentItem-headWrapper">
                                        <div class="CommentItem-head">
                                    <span class="CommentItem-context">
                                        <a href="https://www.zhihu.com/people/gu-rui-80" class="" target="_blank">白鸟</a>
                                        <span class="CommentItem-replyTo">
                                            <span class="CommentItem-replySplit">回复</span>
                                            <a href="https://www.zhihu.com/people/guo-zhi-89-43" class="" target="_blank">沉浮世</a>
                                        </span>
                                    </span>
                                        </div>
                                    </div>
                                    <div class="CommentItem-content">有的家长的宠溺连孩子的成绩都不求，不能理解</div>
                                    <div class="CommentItem-foot">
                                        <span class="CommentItem-like" title="382 人觉得这个很赞">382 赞</span>
                                        <div class="HoverTitle CommentItem-createdTime" data-hover-title="2017 年 11月 27 日星期一晚上 11 点 50 分">
                                            <time datetime="Mon Nov 27 2017 23:50:54 GMT+0800 (中国标准时间)">2 个月前</time>
                                        </div>
                                        {{--<button class="Button CommentItem-action CommentItem-actionReply Button--plain" type="button"><i class="fa fa-reply"></i>回复</button>--}}
                                        <button class="Button CommentItem-action CommentItem-actionLike Button--plain" type="button"><i class="fa fa-thumbs-o-up"></i>赞</button>
                                    </div>
                                </div>
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
        $('.PostComment').on('click','.cancel-review',function () {
            $(this).parent().parent().parent().remove();
        });
        // 点击回复
        $('.PostComment').on('click','.CommentItem-actionReply',function () {
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
        $('.PostComment').on('click','.saveReview',function () {
            var _id = $(this).attr('data-id');
            console.log(_id);
            var content = $(this).parent().parent().find('.richText').val();
            console.log(content);
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
        })
    </script>
@endsection
