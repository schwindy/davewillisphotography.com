function empty(obj)
{
    return is_empty(obj);
}

function is_empty(obj)
{
    if(obj===undefined) return true;
    if(typeof obj==='undefined') return true;
    if(typeof obj==='function') return false;

    if(typeof obj==='string') return obj.trim()==='';

    if(typeof obj==='number' && obj!==0) return false;
    if(obj===false) return true;
    if(obj===null) return true;

    if(typeof obj==='array')
    {
        if(obj.length < 1) return true;
        return false;
    }

    return false;
}

function is_json(str)
{
    if(typeof str==='string' && str.indexOf('{')!== -1 && str.indexOf('}')!== -1) return true;
    return false;
}

function is_object(data)
{
    if(typeof data==='object') return true;
    return false;
}