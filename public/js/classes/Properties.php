<script>
    'use strict';

    function Properties(args)
    {
        if(empty(args)) args = {};
        args.run = empty(args.run)?'admin/kek/save_element':args.run;

        var button_add = $('#add_property');
        var button_submit = $('#kek_submit');
        var container = '.properties';
        var selector = '.property';

        this.listen = function listen(){
            $(selector).each(function(){
                $(this).children('.remove').each(function(){
                    $(this).off('click');
                    $(this).click(function(){
                        $(this).parent().remove();
                        listen();
                    });
                });
            });

            button_add.off('click');
            button_add.click(function(){
                var html =
                    "<div class='property'>"+
                    "<input class='var' placeholder='Property Name'>"+
                    "<input class='val' placeholder='Property Value'>"+
                    "<button class='remove padding_xxsm_y red_bg white bold'>Remove</button>"+
                    "</div>";

                $(container).prepend(html);
                listen();
            });

            button_submit.off('click');
            button_submit.click(function(){
                var properties = __read(selector);
                properties.bio = $('#item_bio').val();

                ajax
                (
                    '/php/portal',
                    {
                        'run':'admin/kek/save_element',
                        'id':$('#id').val(),
                        'display_name':$('#display_name').val(),
                        'type':$('#type').val(),
                        'data':$('#data').val(),
                        'notes':$('#notes').val(),
                        'options':__read('.option'),
                        'properties':properties,
                    },
                    function(response){
                        if(!response.status)
                        {
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
                                location.href = '/admin/kek/'+response.data.path+'/view?id='+response.data.id;
                            }
                        );
                    }
                );
            });
        };

        this.listen();
        return this;
    }
</script>