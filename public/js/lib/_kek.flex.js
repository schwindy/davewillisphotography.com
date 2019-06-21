$(function(){
    $(window).resize(function(){
        kek_flex(".kek_flex");
    });
    setTimeout(function(){
        kek_flex(".kek_flex");
    }, 100)
});

function kek_flex(selector)
{
    var best = -1;
    $(selector).each(function(){
        $(this).height('auto');
        best = Number($(this).height()) > best?Number($(this).height()):best;
    });

    $(selector).each(function(){
        $(this).height(best);
    });
}