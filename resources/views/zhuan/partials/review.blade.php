@section('review')
    <div class="PostComment">
        <div class="BlockTitle av-marginLeft av-borderColor PostComment-blockTitle">
            <span class="BlockTitle-title"><!-- react-text: 84 -->760 条评论<!-- /react-text --></span>
            <span class="BlockTitle-line"></span>
        </div>
        <div class="CommentEditor PostComment-mainEditor">
            <img class="Avatar-hemingway CommentEditor-avatar Avatar--xs" alt="小小梦工场" src="https://pic1.zhimg.com/d18389ea3_xs.jpg" srcset="https://pic1.zhimg.com/d18389ea3_l.jpg 2x">
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
            <div class="CommentItem">
                <a class="UserAvatar CommentItem-author" href="https://www.zhihu.com/people/cheneyfm" target="_blank"><img class="Avatar-hemingway Avatar--xs" alt="文煦" src="https://pic1.zhimg.com/cb7156696_xs.jpg" srcset="https://pic1.zhimg.com/cb7156696_l.jpg 2x"></a>
                <div class="CommentItem-headWrapper">
                    <button class="Button CommentItem-conversationButton Button--plain" type="button" data-toggle="modal" data-target="#commentModal">
                        <i class="fa fa-comments"></i> 查看对话
                    </button>
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
                    <button class="Button CommentItem-action CommentItem-actionReply Button--plain" type="button"><i class="fa fa-reply"></i>回复</button>
                    <button class="Button CommentItem-action CommentItem-actionLike Button--plain" type="button"><i class="fa fa-thumbs-o-up"></i>赞</button>
                </div>
                <div class="CommentEditor CommentItem-inlineReply PostComment-mainEditor">
                    <img class="Avatar-hemingway CommentEditor-avatar Avatar--xs" alt="小小梦工场" src="https://pic1.zhimg.com/d18389ea3_xs.jpg">
                    <div class="CommentEditor-input">
                        <div class="Input-wrapper Input-wrapper--spread Input-wrapper--large Input-wrapper--noPadding">
                            <textarea class="richText form-control" name="postComment" id="comment" rows="2" placeholder="评论由作者筛选后显示"></textarea>
                        </div>
                        <div class="CommentEditor-actions">
                            <button class="Button Button--plain cancelReview" type="button">取消</button>
                            <button class="Button Button--blue saveReview" disabled="" type="button">评论</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="BlockTitle av-marginLeft av-borderColor PostComment-split">
                <span class="BlockTitle-title">以上为精选评论</span>
                <span class="BlockTitle-line"></span>
            </div>
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
                                    <a class="UserAvatar CommentItem-author" href="https://www.zhihu.com/people/cheneyfm" target="_blank"><img class="Avatar-hemingway Avatar--xs" alt="文煦" src="https://pic1.zhimg.com/cb7156696_xs.jpg" srcset="https://pic1.zhimg.com/cb7156696_l.jpg 2x"></a>
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
        $('textarea[name="postComment"]').bind('input propertychange', function() {
            var th = $(this);
            if($(this).val() && $(this).val() !=''){
                $(th).parent().parent().find('.saveReview').removeAttr('disabled');
                $(th).parent().parent().find('.CommentEditor-actions').show();
            }else if(!$(this).val() || $(this).val() !=''){
                $(th).parent().parent().find('.saveReview').attr('disabled','')
            }
        });
        $('.PostComment').on('click','.cancelReview',function () {
            $(this).parent().parent().find('textarea[name="postComment"]').val('');
            $(this).parent().find('.saveReview').attr('disabled','');
            $(this).parent().parent().find('.CommentEditor-actions').hide();
        })
    </script>
@endsection
