function ajax(url, data, success, error, method, dataType, log)
{
    log = !empty(log);

    // Default Ajax Success Callback
    if(typeof success==='undefined' || success==='default')
    {
        success = function(response){
            if(!response.status)
            {
                swal
                (
                    {
                        title:"Error!",
                        text:response.message,
                        type:"error",
                        confirmButtonColor:"#5890ff",
                        confirmButtonText:"Ok"
                    }
                );

                return false;
            }
            else if(response.status===1)
            {
                //@TODO: Insert Sweet Alerts Popup Here
            }
        }
    }

    // Default Ajax Error Callback
    if(typeof error==='undefined' || error==='default')
    {
        error = function(response){
            console.log('Ajax Error(app):');
            console.log('Request Response: ', response);
            console.log('url: ', url);
            console.log('data: ', data);
            console.log('method: ', method);
            console.log('dataType: ', dataType);
            console.log('success: ', success);
            console.log('error: ', error);
        }
    }

    url = (typeof url==='undefined')?PATH_WORLD:url;
    data = (typeof data==='undefined')?null:data;
    method = (typeof method==='undefined')?'POST':method;

    if(typeof dataType==='undefined' || dataType==='default')
    {
        $.ajax
        ({
            type:method,
            url:url,
            data:data,
            success:success,
            error:error,
        });
    }
    else
    {
        dataType = (typeof dataType==='undefined')?'json':dataType;
        $.ajax
        ({
            type:method,
            url:url,
            data:data,
            dataType:dataType,
            success:success,
            error:error,
        });
    }

    if(log)
    {
        console.log('Ajax Sent:');
        console.log('url: ', url);
        console.log('data: ', data);
        console.log('method: ', method);
        console.log('dataType: ', dataType);
        console.log('success: ', success);
        console.log('error: ', error);
    }
}