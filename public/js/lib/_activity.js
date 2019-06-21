/*
function track_activity()
{
    var update_interval = 20000;
    var data =
    {
        "run":"user/track_activity",
        "user_id":kek.USER.id,
        "path":location.pathname+location.search,
        "action":'view',
    };

    var success_callback = function(){setTimeout(track_activity, update_interval);};
    ajax(PATH_PORTAL, data, success_callback);
}
*/