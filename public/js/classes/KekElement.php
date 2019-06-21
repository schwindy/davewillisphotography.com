<script>
    'use strict';

    class KekElement extends __Object {
        constructor(args)
        {
            super(args);
            this.args = args;
            this.button_submit = $('#kek_submit');
            this.data = args.data;
            this.display_name = args.display_name;
            this.id = args.id;
            this.properties = args.properties;
            this.__listen();
            console.log("kekPHP| New Element ("+this.display_name+"): ", this);
        }

        __listen()
        {
            var self = this;
            this.button_submit.off('click');
            this.button_submit.click(function(){
                self.__save();
            });
        }

        __save()
        {
            var properties = __read('.property');
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
                            confirmButtonText:"Leave",
                            cancelButtonColor:"#5890ff",
                            cancelButtonText:"Stay",
                            showCancelButton:true,
                        },
                        function(){
                            var path = $('#type').val().indexOf('shop_item') > -1?'admin/kek/shop':'admin/kek/elements';
                            location.href = '/'+path+'/view?id='+$('#id').val()
                        }
                    );
                }
            );
        }
    }
</script>