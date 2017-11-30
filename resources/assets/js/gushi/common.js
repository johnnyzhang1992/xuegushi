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

$('.poem-tool').on('click','.like',function () {
    var type = $(this).attr('data-type');
    var id = $(this).attr('data-id');
    var like = $(this).find('.like_count');
    $.post(
        '/ajax/update/like_count',
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
});