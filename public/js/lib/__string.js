function br2nl(str)
{
    var str = new String(str);
    str = str.replace(/<br>/gi, "\n");
    return str.replace(/<br \/>/gi, "");
}

function nl2br(str)
{
    var str = new String(str);
    str = br2nl(str);
    return str.replace(/\n/gi, "<br>").replace(/\r/gi, "<br>");
}

function str_clean(str)
{
    if(empty(str)) return false;

    var result = "";
    str = str.trim();
    for(var i = 0; i < str.length; i++)
    {
        var c = str.charAt(i);
        if(c >= "a" && c <= "z") result = result+c;
        else if(c >= "A" && c <= "Z") result = result+c;
        else if(c >= "0" && c <= "9") result = result+c;
    }

    return result;
}

function str_upper_first(str)
{
    return str.charAt(0).toUpperCase()+str.slice(1);
}