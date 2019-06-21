$(function(){
    __init_carousel();
});

function __init_carousel()
{
    $('.owl-carousel').each(function(){
        $(this).owlCarousel
        (
            {
                items:3,
                responsiveClass:true,
                responsiveBaseWidth:"#Container",
                responsiveRefreshRate:500,
                slideSpeed:600,
                mouseDrag:false,
                autoPlay:5000,
                scrollPerPage:true,
            }
        );
    });
}