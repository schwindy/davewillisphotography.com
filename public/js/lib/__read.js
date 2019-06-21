function __read(selector)
{
    if(empty(selector)) return false;

    var data = {};
    $(selector).each(function(){
        var field = $(this).children('.var').val();
        var val = $(this).children('.val').val();
        var notes = $(this).children('.notes').val();

        if(!empty(field))
        {
            data[field] = val;
            if(!empty(notes))
            {
                data[field] = {};
                data[field]['val'] = val;
                data[field]['notes'] = notes;
            }
        }
    });

    return data;
}