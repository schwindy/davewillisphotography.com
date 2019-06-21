function __alert(message, type, args)
{
    args = empty(args)?{}:args;
    args.message = empty(message)?"__alert(): Message is empty":message;
    args.confirm_callback = empty(args.confirm_callback)?function(){
    }:args.confirm_callback;
    args.confirm_color = empty(args.confirm_color)?"#5890ff":args.confirm_color;
    args.confirm_text = empty(args.confirm_text)?"Ok":args.confirm_text;
    args.cancel_callback = empty(args.cancel_callback)?function(){
    }:args.cancel_callback;
    args.cancel_color = empty(args.cancel_color)?"#5890ff":args.cancel_color;
    args.cancel_text = empty(args.cancel_text)?"Cancel":args.cancel_text;
    args.cancel_show = empty(args.cancel_show)?false:args.cancel_show;

    args.type = empty(type)?"error":type;
    args.title = empty(args.title)?'Error!':args.title;

    if(type==='warning') args.title = 'Warning';
    if(type==='info') args.title = 'Info';
    if(type==='success') args.title = 'Success!';

    swal
    (
        {
            title:args.title,
            text:args.message,
            type:args.type,
            confirmButtonColor:args.confirm_color,
            confirmButtonText:args.confirm_text,
            cancelButtonColor:args.cancel_color,
            cancelButtonText:args.cancel_text,
            showCancelButton:args.cancel_show,
        },
        args.confirm_callback,
        args.cancel_callback
    );
}