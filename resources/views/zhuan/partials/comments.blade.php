<?php
use App\Helpers\DateUtil;
?>
@if(isset($comments) && $comments)
    @foreach($comments as $key=>$comment)
        <div class="CommentItem">
            <a class="UserAvatar CommentItem-author" href="{{url($comment->domain ? 'people/'.$comment->domain : 'people/'.$comment->u_id)}}" target="_blank"><img class="Avatar-hemingway Avatar--xs" alt="{{@$comment->name}}" src="{{asset(@$comment->avatar)}}"></a>
            <div class="CommentItem-headWrapper">
                @if(isset($type) && $type !='conversation')
                    @if(isset($comment->parent_id) && $comment->parent_id>0)
                        <button class="Button CommentItem-conversationButton Button--plain" type="button" data-id="{{@$comment->parent_id}}">
                            <i class="fa fa-comments"></i> 查看对话
                        </button>
                    @endif
                @endif
                <div class="CommentItem-head">
                    <span class="CommentItem-context">
                        <a href="{{url($comment->domain ? 'people/'.$comment->domain : 'people/'.$comment->u_id)}}" class="" target="_blank">{{@$comment->name}}</a>@if($user_id == $comment->u_id)<span class="CommentItem-authorTitle">（作者）</span> @endif
                        @if(isset($comment->parent_id) && $comment->parent_id>0)
                            <span class="CommentItem-replyTo">
                                <span class="CommentItem-replySplit">回复</span>
                                <a href="{{url($comment->p_domain ? 'people/'.$comment->p_domain : 'people/'.$comment->p_u_id)}}" class="" target="_blank">{{@$comment->p_name}}</a>@if($user_id == $comment->u_id)<span class="CommentItem-authorTitle">（作者）</span> @endif
                            </span>
                        @endif
                    </span>
                </div>
            </div>
            <div class="CommentItem-content">{{@$comment->content}}</div>
            <div class="CommentItem-foot">
                <span class="CommentItem-like" title="{{@$comment->like_count}}  人觉得这个很赞">{{@$comment->like_count}} 赞</span>
                <div class="HoverTitle CommentItem-createdTime" data-hover-title="2017 年 11月 27 日星期一晚上 11 点 50 分">
                    <time datetime="Mon Nov 27 2017 23:50:54 GMT+0800 (中国标准时间)">{{@ DateUtil::formatDate(strtotime($comment->created_at))}}</time>
                </div>
                <button class="Button CommentItem-action CommentItem-actionReply Button--plain" type="button" data-id="{{@$comment->id}}"><i class="fa fa-reply"></i>回复</button>
                <button class="Button CommentItem-action CommentItem-actionLike Button--plain" type="button"><i class="fa fa-thumbs-o-up"></i>赞</button>
                @if ((!Auth::guest() && Auth::user()->id == $comment->u_id) || (!Auth::guest() && Auth::user()->id==1))
                    <button class="Button CommentItem-action CommentItem-actionDelete Button--plain" data-id="{{@$comment->id}}" type="button">删除</button>
                @endif
            </div>
        </div>
        @if($key == 2 && $comments->currentPage()==1)
            {{--这部分只有第一页存在--}}
            <div class="BlockTitle av-marginLeft av-borderColor PostComment-split">
                <span class="BlockTitle-title">以上为精选评论</span>
                <span class="BlockTitle-line"></span>
            </div>
        @endif
    @endforeach
@endif
<div class="PostComment-pagination" style="text-align: center">
    @if(isset($type) && $type !='conversation')
        {{@$comments->links()}}
    @endif
</div>