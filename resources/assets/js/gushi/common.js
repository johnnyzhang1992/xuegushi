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