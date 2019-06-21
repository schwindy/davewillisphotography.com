function edit_popup(args)
{
    open_edit_popup();
    // console.log("New Edit Popup Created");
    // console.log('args: ', args);

    var popup_container = $('.edit_popup');
    var popup_inputs = $('.edit_popup').children('fieldset').children('div').children('input');
    var buttons = $(popup_container).children('.sa-button-container');
    var confirm_button = $(buttons).children('.confirm');
    var cancel_button = $(buttons).children('.cancel');

    var data = {'run':args.run};

    $(confirm_button).click(function(){
        $(popup_inputs).each(function(){
            if(empty($(this).val().trim()))
            {
                $('.sa-error-container').addClass('show');
                $('.sa-input-error').addClass('show');
                return false;
            }
        });

        if(!read_fields())
        {
            return false;
        }

        $('.edit_popup').velocity
        (
            "transition.slideUpBigOut",
            {
                duration:225,
                complete:function(){
                    $(this).remove();
                    send_edit_popup_request();
                }
            }
        );
    });

    $(cancel_button).click(close_edit_popup);

    function close_edit_popup()
    {
        $('.opacity_1').velocity
        (
            "transition.fadeOut",
            {
                duration:375,
                begin:function(){
                    $('.edit_popup').velocity
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

    function open_edit_popup()
    {
        $('.opacity_1').velocity
        (
            "transition.fadeIn",
            {
                duration:325,
                begin:function(){
                    $('body').prepend(args.body);
                }

            }
        );

        $('.edit_popup').velocity
        (
            "transition.slideDownBigIn", {duration:475}
        );
    }

    function read_fields()
    {
        // Example: args.fields
        //
        // {
        //     "current_email":
        //     {
        //         'selector':'#token_email',
        //     },
        //     "username":
        //     {
        //         'selector':'#token_username',
        //     },
        //     "new_email":
        //     {
        //         'selector':'#'+type,
        //         'check_method':checkEmailAddress,
        //         'match':'#confirm_new_'+type,
        //         'not_match':'#token_email',
        //     },
        // }
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

    function send_edit_popup_request()
    {
        if(!empty(data.__id)) data.id = data.__id;

        ajax
        (
            PATH_PORTAL,
            data,
            function(response){
                if(!response.status)
                {
                    console.log('Popup Response(Error): ', {'data':data, 'response':response});
                    __alert(response.message);
                    return false;
                }

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
                        close_edit_popup();
                        setTimeout(function(){
                            location.reload()
                        }, 100);
                    }
                );
            },
            function(response){
                console.log('Popup Response(Error): ', response);
                __alert('__popup: Ajax Error', response);
            }
        );
    }
}

$(function(){
    if(!$('.opacity_1').length) $('body').prepend("<div class='opacity_1'></div>");

    $('.edit_button').on("click", function(){
        if($(this).hasClass('disable_edit')) return false;

        var inputs;
        var fields = {};
        var __id = empty($(this).attr('__id'))?'':$(this).attr('__id');
        var run = empty($(this).attr('run'))?'empty':$(this).attr('run');
        var type = empty($(this).prop('id'))?'default':$(this).prop('id');

        var title = empty($(this).attr('title'))?'Edit '+str_upper_first(type):$(this).attr('title');
        var subtitle = empty($(this).attr('subtext'))?'Enter your '+str_upper_first(type):$(this).attr('subtext');
        var error_text = empty($(this).attr('error_text'))?'You need to write something!':$(this).attr('error_text');
        var cancel_text = empty($(this).attr('cancel_text'))?'Cancel':$(this).attr('cancel_text');
        var confirm_text = empty($(this).attr('confirm_text'))?'Ok':$(this).attr('confirm_text');

        if(type==='display_name')
        {
            var placeholder = empty($(this).attr('input_placeholder'))?'Display Name':$(this).attr('input_placeholder');
            fields =
                {
                    "username":
                        {
                            'selector':'#token_username'
                        },
                    "display_name":
                        {
                            'selector':'#display_name',
                            'check_empty':true,
                        },
                };

            inputs =
                "<div>"+
                "<input id='"+type+"' autofocus placeholder='"+placeholder+"'>"+
                "<div class='sa-input-error'></div>"+
                "</div>";
        }
        else if(type==='email')
        {
            fields =
                {
                    "current_email":
                        {
                            'selector':'#token_email',
                        },
                    "username":
                        {
                            'selector':'#token_username',
                        },
                    "new_email":
                        {
                            'selector':'#'+type,
                            'check_method':checkEmailAddress,
                            'match':'#confirm_new_'+type,
                            'not_match':'#token_email',
                        },
                };

            inputs =
                "<div>"+
                "<input id='"+type+"' class='new_email' autofocus placeholder='New email' type='email'>"+
                "<div class='sa-input-error'></div>"+
                "</div>"+
                "<div>"+
                "<input id='confirm_new_"+type+"' class='confirm_new_email' placeholder='Confirm Email' type='email'>"+
                "<div class='sa-input-error'></div>"+
                "</div>";
        }
        else if(type==='password')
        {
            fields =
                {
                    "username":
                        {
                            'selector':'#token_username',
                        },
                    "current_password":
                        {
                            'selector':'#current_password',
                        },
                    "new_password":
                        {
                            'selector':'#confirm_new_'+type,
                            'match':'#confirm_new_'+type,
                        },
                };

            inputs =
                "<div>"+
                "<input id='hidden' placeholder='Current Password' type='password' style='display:none !important;'>"+
                "<input id='current_password' autofocus placeholder='Current Password'>"+
                "<div class='sa-input-error'></div>"+
                "</div>"+
                "<div>"+
                "<input id='new_password' placeholder='New password'>"+
                "<div class='sa-input-error'></div>"+
                "</div>"+
                "<div>"+
                "<input id='confirm_new_password' placeholder='Confirm new password'>"+
                "<div class='sa-input-error'></div>"+
                "</div>";
        }
        else if(type==='subscription_cancel')
        {
            swal
            (
                {
                    title:"Are you sure?",
                    text:"We are sorry to see you go.",
                    type:"warning",
                    showCancelButton:true,
                    confirmButtonColor:"rgb(54, 90, 244)",
                    confirmButtonText:"Yes",
                    closeOnConfirm:true
                },
                function(){
                    var data =
                        {
                            "run":"user/subscription_cancel",
                            "user_id":$('#token_user_id').val().trim(),
                        };

                    ajax(PATH_PORTAL, data, function(){
                        location.reload();
                    });
                }
            );
            return true;
        }
        else if(type==='username')
        {
            var placeholder = empty($(this).attr('input_placeholder'))?'Username':$(this).attr('input_placeholder');
            fields =
                {
                    "email":
                        {
                            'selector':'#token_email',
                        },
                    "current_username":
                        {
                            'selector':'#token_username',
                        },
                    "new_username":
                        {
                            'selector':'#'+type,
                        },
                };

            inputs =
                "<div>"+
                "<input id='"+type+"' autofocus placeholder='"+placeholder+"'>"+
                "<div class='sa-input-error'></div>"+
                "</div>";
        }
        else
        {
            var value = empty($(this).attr('input_default'))?'':$(this).attr('input_default');
            var placeholder = empty($(this).attr('input_placeholder'))?'Enter a value':$(this).attr('input_placeholder');

            fields['__id'] =
                {
                    'selector':'#__id',
                };

            fields[type] =
                {
                    'selector':'#'+type,
                };

            inputs =
                "<div>"+
                "<input id='__id' type='hidden' value='"+__id+"'>"+
                "<input id='"+type+"' autofocus placeholder='"+placeholder+"' value='"+value+"'>"+
                "<div class='sa-input-error'></div>"+
                "</div>";
        }

        var body =
            "<div class='sweet-alert edit_popup'>"+
            "<h1 class='title'>"+title+"</h1>"+
            "<p class='subtext'>"+subtitle+"</p>"+
            "<fieldset>"+inputs+"</fieldset>"+
            "<div class='sa-error-container'>"+
            "<div class='icon'></div>"+
            "<p class='error_message'>"+error_text+"</p>"+"</div>"+
            "<div class='sa-button-container'>"+
            "<button class='confirm theme_color'>"+confirm_text+"</button>"+
            "<button class='cancel'>"+cancel_text+"</button>"+
            "</div>"+
            "</div>";

        edit_popup
        (
            {
                "run":run,
                "type":type,
                "fields":fields,
                "body":body
            }
        );
    });
});