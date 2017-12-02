/**
 * Author johnnyZhang
 * Site johnnyzhang.cn
 * CreateTime 2017/11/21.
 */
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
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
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
                if($(th).hasClass('active')){
                }else{
                    $(th).addClass('active');
                    $(th).find('i').removeClass('fa-heart-o');
                    $(th).find('i').addClass('fa-heart');
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
                $(th).find('i').removeClass('fa-heart');
                $(th).find('i').addClass('fa-heart-o');
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
$(document).ready(function () {
    // 判断诗词是否已经点击了like
    // if($('input[name="_user_id"]')){
    //     $('.poem-card .like').each(function (index,el) {
    //         $.post(
    //             '/ajax/judge/like',
    //             {
    //                 'id': $(el).attr('data-id'),
    //                 'type': $(el).attr('data-type') ,
    //                 '_token': $('input[name="_token"]').val()
    //             },
    //             function (res) {
    //                 if(res && res.status){
    //                     $(el).addClass('active')
    //                 }
    //             }
    //         )
    //     });
    // }
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
    $('.speaker-close').on('click',function () {
        $(this).parent().parent().hide();
    })
});