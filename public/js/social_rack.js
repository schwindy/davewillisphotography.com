$(function()
{
    if($('.panel-3').hasClass('trigger-contact'))
    {
        $('.panel-3').css({"background-color": "#42a5f5"});

        $('.panel-3').mouseleave(function()
        {
            $('.panel-3').css({"background-color": "#42a5f5"});
        });
    }

    //facebook
    $('.a-1').mouseenter(function()
    {
        $('.panel-3').css({"background-color": "#3b5998"});
    });

    //twitter
    $('.a-2').mouseenter(function()
    {
        $('.panel-3').css({"background-color": "#55acee"});
    });

    //youtube
    $('.a-3').mouseenter(function()
    {
        $('.panel-3').css({"background-color": "#cd201f"});
    });

    //google +
    $('.a-4').mouseenter(function()
    {
        $('.panel-3').css({"background-color": "#dd4b39"});
    });

    //github
    $('.a-5').mouseenter(function()
    {
        $('.panel-3').css({"background-color": "#2ecc71"});
    });

    //default bg color
    $('.panel-3').mouseleave(function()
    {
        if(!$('.panel-3').hasClass('trigger-contact'))
        {
            $('.panel-3').css({"background-color": "#2d3538"});
        }
    });
});