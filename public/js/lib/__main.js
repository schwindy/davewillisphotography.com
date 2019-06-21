$(function(){
    if(typeof Kek==='undefined')
    {
        if(CURRENT_PAGE==="login")
        {
            __alert
            (
                "Your browser is out of date!\n\n"+
                "Please install Google Chrome to make sure everything works properly on our end.\n\n"+
                "We apologize for any inconvenience."
            );
        }
        return false;
    }

    for(var key in __logs)
    {
        var log = __logs[key];
        kek.log(log.message, log.data);
    }
});