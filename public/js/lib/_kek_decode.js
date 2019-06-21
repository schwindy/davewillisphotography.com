function __kek_decode(str, obj, dry)
{
    dry = empty(dry)?false:true;
    var token_start = '__@';
    var token_end = '@__';

    while(str.indexOf(token_start)!== -1)
    {
        var start = str.indexOf(token_start);
        var end = str.indexOf(token_end)+token_end.length;
        if(end < start)
        {
            if(end >= 10) start = end-10;
            else start = 0;
            console.warn('kekPHP| Syntax Error Near: ', str.substring(start, end));
            break;
        }

        var token_full = str.substring(start, end);
        var token = token_full.replace(token_start, '').replace(token_end, '');
        if(!empty(obj[token]) && !dry) str = str.replace(token_full, obj[token].trim());
        else str = str.replace(token_full, '@'+token+'');
    }

    str = str.replace(/br\|>/gi, '</br>');
    str = str.replace(/((\w*)([|][>]))/gi, function(x){
        return '<'+x.replace('|>', '>');
    });
    str = str.replace(/(([<][|])(\w*))/gi, function(x){
        return x.replace('<|', '</')+'>';
    });
    str = str.replace(/<br>/gi, '');

    // Custom Element Behaviors
    str = str.replace(/<card>/gi, '<div class="card">').replace(/<\/card>/gi, '</div>');
    str = str.replace(/<i>/gi, '<i class="material-icons">');
    str = str.replace(/<search>/gi, '<form class="kek_search"><input id="q" placeholder="Search"></form>').replace(/<\/search>/gi, '</div>');
    str = str.replace(/<\/img>/gi, '');
    return str;
}