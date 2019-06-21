function __edit(args)
{
    var data = {'run':args.run};
    var overlay_class = 'opacity_1';
    var overlay_selector = '.'+overlay_class;
    __construct();

    function __construct()
    {
        __open();
    }

    function __close()
    {
        $(overlay_selector).velocity
        (
            "transition.fadeOut",
            {
                duration:375,
                begin:function(){
                    $('.__edit').velocity
                    (
                        "transition.slideUpBigOut",
                        {
                            duration:225,
                            complete:function(){
                                $(this).remove();
                            }
                        }
                    );
                }
            }
        );
    }

    function __listen()
    {
        var popup_container = $('.__edit');
        var popup_inputs = $('.__edit').children('fieldset').children('div').children('input');
        var buttons = $(popup_container).children('.sa-button-container');
        var cancel_button = $(buttons).children('.cancel');
        var confirm_button = $(buttons).children('.confirm');

        $(confirm_button).click(function(){
            $(popup_inputs).each(function(){
                if(empty($(this).val().trim()))
                {
                    $('.sa-error-container').addClass('show');
                    $('.sa-input-error').addClass('show');
                    return false;
                }
            });

            if(!__read()) return false;

            $('.__edit').velocity
            (
                "transition.slideUpBigOut",
                {
                    duration:225,
                    complete:function(){
                        $(this).remove();
                        __submit();
                    }
                }
            );
        });

        $(cancel_button).click(__close);
    }

    function __open()
    {
        if(!$(overlay_selector).length) $('body').prepend("<div class='"+overlay_class+"'></div>");

        $(overlay_selector).velocity
        (
            "transition.fadeIn",
            {
                duration:325,
                begin:function(){
                    $('body').prepend(args.body);
                },
                complete:function(){
                    $('.__edit').children('fieldset').children('div').children("#"+args.type).select();
                    __listen();
                }
            }
        );

        $('.__edit').velocity("transition.slideDownBigIn", {duration:475});
    }

    function __read()
    {
        for(var field_name in args.fields)
        {
            var obj = args.fields[field_name];
            var selector_val = $(obj.selector).val().trim();

            // If this field has a check_method, run the check_method
            if(obj.hasOwnProperty('check_method'))
            {
                var result = obj.check_method(selector_val);

                if(!result[0])
                {
                    swal
                    (
                        {
                            title:"Error!",
                            text:result[1],
                        }
                    );
                    return false;
                }
            }

            // If this field has a check_method, run the check_method
            if(obj.hasOwnProperty('check_empty'))
            {
                if(empty(selector_val))
                {
                    swal
                    (
                        {
                            title:"Error!",
                            text:"Field is empty!",
                        }
                    );
                    return false;
                }
            }

            // If this field has a match property, require selector_val does match.val()
            if(obj.hasOwnProperty('match'))
            {
                // ex: email & confirm_email do not match
                if(selector_val!==$(obj.match).val().trim())
                {
                    swal
                    (
                        {
                            title:"Error!",
                            text:"Fields do not match",
                        }
                    );
                    return false;
                }
            }

            // If this field has a not_match property, require selector_val does not_match.val()
            if(obj.hasOwnProperty('not_match'))
            {
                // ex: cannot change email to current email
                if(selector_val===$(obj.not_match).val().trim())
                {
                    swal
                    (
                        {
                            title:"Error!",
                            text:"Fields match",
                        }
                    );
                    return false;
                }
            }

            data[field_name] = selector_val;
        }

        return true;
    }

    function __submit()
    {
        data.id = data.__id;

        if(data.run==='__kek')
        {
            var edit_button = $('#'+args.button_id);
            var edit_text = edit_button.children('span').first();
            if(args.value_original==='') edit_text.html(edit_text.html()+" "+data[args.type]);
            else edit_text.html(edit_text.html().replace(args.value_original, data[args.type]));
            edit_button.attr('input_default', data[args.type]);
            __close();
            return true;
        }

        data.user_id = kek.USER.id;

        ajax
        (
            PATH_PORTAL,
            data,
            function(response){
                if(!response.status)
                {
                    console.error('__edit(): '+response.message, {'data':data, 'response':response});
                    __alert(response.message);
                    __close();
                    return false;
                }

                __close();

                swal
                (
                    {
                        title:"Success!",
                        text:response.message,
                        type:"success",
                        confirmButtonColor:"#5890ff",
                        confirmButtonText:"Ok"
                    },
                    function(){
                        var edit_button = $('#'+args.button_id+'.__edit_button');
                        var edit_text = edit_button.children('span').first();

                        if(!empty(edit_text))
                        {
                            if(args.value_original==='') edit_text.html(edit_text.html()+" "+data[args.type]);
                            else edit_text.html(edit_text.html().replace(args.value_original, data[args.type]));
                        }

                        edit_button.attr('input_default', data[args.type]);
                        args.value_original = data[args.type];
                    }
                );
            },
            function(response){
                console.error('__edit(): ', {'data':data, 'response':response});
                __alert('__edit(): Request failure...', response);
                __close();
            }
        );
    }
}

var edit;
$(function(){
    $('.__edit_button').each(function(){
        $(this).off('click');
        $(this).click(function(){
            if($(this).hasClass('__disable')) return false;

            var __id = empty($(this).attr('__id'))?'':$(this).attr('__id');
            var __run = empty($(this).attr('run'))?'empty':$(this).attr('run');
            var __type = empty($(this).prop('id'))?'default':$(this).prop('id');

            var cancel_text = empty($(this).attr('cancel_text'))?'Cancel':$(this).attr('cancel_text');
            var confirm_text = empty($(this).attr('confirm_text'))?'Ok':$(this).attr('confirm_text');
            var error_text = empty($(this).attr('error_text'))?'You need to write something!':$(this).attr('error_text');
            var placeholder = empty($(this).attr('input_placeholder'))?'Enter a value':$(this).attr('input_placeholder');
            var subtitle = empty($(this).attr('subtext'))?'Enter your '+str_upper_first(__type):$(this).attr('subtext');
            var title = empty($(this).attr('title'))?'Edit '+str_upper_first(__type):$(this).attr('title');
            var value = empty($(this).attr('input_default'))?'':$(this).attr('input_default');

            var fields =
                {
                    '__id':
                        {
                            'selector':'#__id',
                        },
                };

            fields[__type] =
                {
                    'selector':'#'+__type,
                };

            var inputs =
                "<div>"+
                "<input id='__id' type='hidden' value='"+__id+"'>"+
                "<input id='"+__type+"' placeholder='"+placeholder+"' value=\""+value+"\" autofocus>"+
                "<div class='sa-input-error'></div>"+
                "</div>";

            var body =
                "<div class='sweet-alert __edit'>"+
                "<h1 class='title'>"+title+"</h1>"+
                "<p class='subtext'>"+subtitle+"</p>"+
                "<fieldset>"+inputs+"</fieldset>"+
                "<div class='sa-error-container'>"+
                "<div class='icon'></div>"+
                "<p class='error_message'>"+error_text+"</p>"+
                "</div>"+
                "<div class='sa-button-container'>"+
                "<button class='confirm theme_color'>"+confirm_text+"</button>"+
                "<button class='cancel'>"+cancel_text+"</button>"+
                "</div>"+
                "</div>";

            edit = __edit
            (
                {
                    "run":__run,
                    "type":__type,
                    "fields":fields,
                    "body":body,
                    "button_id":$(this).prop('id'),
                    "value_original":value,
                }
            );
        });
    });
});