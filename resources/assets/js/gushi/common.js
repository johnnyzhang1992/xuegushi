/**
 * Author johnnyZhang
 * Site johnnyzhang.cn
 * CreateTime 2017/11/21.
 */
// 返回顶部
jQuery(document).ready(function(){
    // hide #back-top first
    jQuery("#goTop").hide();
    // fade in #back-top
    jQuery(function () {
        jQuery(window).scroll(function () {
            // console.log("jQuery(this).scrollTop()="+jQuery(this).scrollTop());
            if (jQuery(this).scrollTop() > 250) {
                jQuery('#goTop').fadeIn();
            } else {
                jQuery('#goTop').fadeOut();
            }
        });
        // scroll body to 0px on click
        jQuery('#goTop .to_top').click(function () {
            jQuery('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
    });
});
// 喜欢
$('.poem-tool').on('click','.like',function () {
    var type = $(this).attr('data-type');
    var id = $(this).attr('data-id');
    var like = $(this).find('.like_count');
    $.post(
        '/ajax/update/like',
        {
            'id': id,
            'type': type,
            '_token': $('input[name="_token"]').val()
        },
        function (res) {
            if(res && res.status == 'active'){
               $(like).html(res.num);
               if($(like).parent().hasClass('active')){

               }else{
                   $(like).parent().addClass('active')
               }
                $('body').toast({
                    position:'fixed',
                    content:res.msg,
                    duration:1000,
                    isCenter:true,
                    background:'rgba(51,122,183,0.8)',
                    animateIn:'bounceIn-hastrans',
                    animateOut:'bounceOut-hastrans'
                });
            }else if(res && res.status == 'delete'){
                $(like).html(res.num);
                if($(like).parent().hasClass('active')){
                    $(like).parent().removeClass('active')
                }
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
});
// 收藏
$('.poem-tool').on('click','.collect',function () {
    var type = $(this).attr('data-type');
    var id = $(this).attr('data-id');
    var th = $(this);
    $.post(
        '/ajax/update/collect',
        {
            'id': id,
            'type': type,
            '_token': $('input[name="_token"]').val()
        },
        function (res) {
            if(res && res.status == 'active'){
                $(th).addClass('active');
                $(th).find('i').removeClass('fa-star-o');
                $(th).find('i').addClass('fa-star');
                if($(th).find('.tool-name')){
                    $(th).find('.tool-name').html('已收藏');
                }
                $('body').toast({
                    position:'fixed',
                    content:res.msg,
                    duration:1000,
                    isCenter:true,
                    background:'rgba(51,122,183,0.8)',
                    animateIn:'bounceIn-hastrans',
                    animateOut:'bounceOut-hastrans'
                });
            }else if(res && res.status == 'delete'){
                $(th).removeClass('active');
                $(th).find('i').removeClass('fa-star');
                $(th).find('i').addClass('fa-star-o');
                if($(th).find('.tool-name')){
                    $(th).find('.tool-name').html('收藏');
                }
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
});
// 朗读
$('.poem-tool').on('click','.speaker',function () {
    var type = $(this).attr('data-type');
    var id = $(this).attr('data-id');
    var th = $(this);
    var target_voice = $('#speaker-'+ id);
    if($(this).attr('data-status') != 'active'){
        target_voice.append('         <div class="col-md-9 no-padding">\n' +
            '                                    <audio style="cursor:pointer;width:100%;" src="/static/audios/welcome.mp3" controls="controls">\n' +
            '                                        <source src="/static/audios/welcome.mp3" type="audio/mpeg">\n' +
            '                                    </audio>\n' +
            '                                    <div style="color: #999;font-size: 12px;padding-left: 15px">以上音频由百度语音合成技术合成</div>\n' +
            '                                </div>\n' +
            '                                <div class="col-md-3">\n' +
            '                                    <a type="button" class="speaker-close" style="line-height: 32px;cursor: pointer">点击收起 <i class="fa fa-eject"></i></a>\n' +
            '                                </div>');
        $('body').toast({
            position:'fixed',
            content:'音频加载中，请稍等...',
            duration:2000,
            isCenter:true,
            background:'rgba(51,122,183,0.8)',
            animateIn:'bounceIn-hastrans',
            animateOut:'bounceOut-hastrans'
        });
        $.get(
            '/ajax/voiceCombine',
            {
                'id': id,
                'type': type,
                '_token': $('input[name="_token"]').val()
            },
            function (res) {
                if(res && res.status == 'success'){
                    $(target_voice).find('audio').attr('src',res.src);
                    $(target_voice).find('source').attr('src',res.src);
                    $(target_voice).show();
                    $(th).attr('data-status','active');
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
        $(target_voice).show();
    }
});
// sentence
$('.SentenceItemList').on('click','.s-content',function () {
    var th = $(this);
    $('.sentenceItem').not('#sentence-'+$(this).attr('data-id')).removeClass('selected');
    $('.sentenceItem').not('#sentence-'+$(this).attr('data-id')).find('.sentenceTool').fadeOut();
    $('.sentenceItem').not('#sentence-'+$(this).attr('data-id')).find('.sentenceMeta').fadeOut();
    if($(this).parent().parent().hasClass('selected')){
        $(th).parent().parent().removeClass('selected');
        $(this).parent().find('.sentenceTool').fadeOut();
        $(this).parent().find('.sentenceMeta').fadeOut();
    }else{
        $(th).parent().parent().addClass('selected');
        $(this).parent().find('.sentenceTool').fadeIn();
        $(this).parent().find('.sentenceMeta').fadeIn();
    }

});
$(document).ready(function () {
    // 复制
    var clipboard = new Clipboard('.copy');
    clipboard.on('success', function(e) {
        $('body').toast({
            position:'fixed',
            content:'复制成功',
            duration:1000,
            isCenter:true,
            background:'rgba(51,122,183,0.8)',
            animateIn:'bounceIn-hastrans',
            animateOut:'bounceOut-hastrans'
        });
    });
    $('.poem-card').on('click','.speaker-close',function () {
        $(this).parent().parent().hide();
    });
    if($(window).width()>768){
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
    }
    $('.wechat-qrcode').popover({
        'animation':true,
        'html':true,
        'placement':'bottom',
        'trigger': 'hover | focus | click',
        'content':'<img src="/static/common/wechat.jpg" style="width:100%" alt="">'
    });
    function getSearchResult() {
        $.get(
            '/search',
            {
                'value': $('#search-input').val(),
                '_token': $('input[name="_token"]').val()
            },
            function (res) {
                $('#box').html(res);
                var content = $('.neibox').html();
                if(content == null || content.length == 0 || content == '  '){
                    $('.neibox').html("<div class=\"no-result\" style=\"padding: 10px 15px\">\n" +
                        "                            <p>没有找打相关内容哦！</p>\n" +
                        "                            <p>换个关键词试试(:-</p>\n" +
                        "                        </div>")
                }
                $('.search-box').show();
            }
        )
    }
    $('#search-now').on('click',function () {
        if($('#search-input').val() !=''){
            getSearchResult();
        }else{
            $('body').toast({
                position:'fixed',
                content: '请确保输入了内容(:-',
                duration:1000,
                isCenter:true,
                background:'rgba(0,0,0,0.5)',
                animateIn:'bounceIn-hastrans',
                animateOut:'bounceOut-hastrans'
            });
        }
    });
    $('#search-input').keydown(function (event) {
        if (event.keyCode == 13) {
            //执行操作
            if($('#search-input').val() !=''){
                getSearchResult();
            }else{
                $('body').toast({
                    position:'fixed',
                    content: '请确保输入了内容(:-',
                    duration:1000,
                    isCenter:true,
                    background:'rgba(0,0,0,0.5)',
                    animateIn:'bounceIn-hastrans',
                    animateOut:'bounceOut-hastrans'
                });
            }
        }
    });
    $('#search-input').change(function (event) {
        if (event.keyCode == 13) {
            //执行操作
            if($('#search-input').val() !=''){
                getSearchResult();
            }else{
                $('body').toast({
                    position:'fixed',
                    content: '请确保输入了内容(:-',
                    duration:1000,
                    isCenter:true,
                    background:'rgba(0,0,0,0.5)',
                    animateIn:'bounceIn-hastrans',
                    animateOut:'bounceOut-hastrans'
                });
            }
        }
    });
    $('#search-input').bind('input propertychange', function() {
        if($('#search-input').val() !=''){
            getSearchResult();
        }
    });
    $(document).click(function(){
      $('#box').hide()
    })
});