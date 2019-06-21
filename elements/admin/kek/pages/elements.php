<?php
$page = new Page($db->get_row("SELECT * FROM pages WHERE id='$_REQUEST[id]'"));

echo __html('card', [
    'class' => 'text_center',
    'text'  => __html('h1', "Manage Page Elements")
]);

echo generate_elements_edit_element($page);
?>
<script>
    var elements = [];
    $(function(){
        elements = read_elements();

        $('#submit').click(function(){
            elements = read_elements();

            ajax
            (
                '/php/portal',
                {
                    'run':'admin/kek/edit_page_elements',
                    'id':'<?php echo $_REQUEST['id']?>',
                    'elements':elements,
                },
                function(response){
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
                            location.href = '/admin/kek/pages/view?id=<?php echo $_REQUEST['id']?>'
                        }
                    );
                }
            );
        });

        attach_clicks();
    });

    function attach_clicks()
    {
        $('.element').each(function(){
            $(this).children('.move_up').each(function(){
                $(this).off('click');
                $(this).click(function(){
                    if(empty($(this).parent().prev().html()))
                    {return false;}

                    var id = $(this).parent().prop('id');
                    var html = "<div id='"+id+"' class='element'>"+$(this).parent().html()+"</div>";
                    $(this).parent().prev().before(html);
                    $(this).parent().remove();
                    elements = read_elements();
                    attach_clicks();
                });
            });

            $(this).children('.move_down').each(function(){
                $(this).off('click');
                $(this).click(function(){
                    if(empty($(this).parent().next().html()))
                    {return false;}

                    var id = $(this).parent().prop('id');
                    var html = "<div id='"+id+"' class='element'>"+$(this).parent().html()+"</div>";
                    $(this).parent().next().after(html);
                    $(this).parent().remove();
                    elements = read_elements();
                    attach_clicks();
                });
            });

            $(this).children('.remove').each(function(){
                $(this).off('click');
                $(this).click(function(){
                    $(this).parent().remove();
                    elements = read_elements();
                    attach_clicks();
                });
            });
        });
    }

    function read_elements()
    {
        var elements = [];

        $('.element').each(function(){
            elements.push($(this).prop('id'));
        });

        return elements;
    }
</script>
