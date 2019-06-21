<?php
echo __html('card', [
    'class' => 'text_center',
    'text'  => __html('h1', ['text' => "Add Page Element"]) . generate_elements_dropdown('element_id') . "<button id='submit' class='width_100 padding_sm_y border_0 blue_bg' title='Submit'>
                <img src='/img/icons/input_icon.png'>
                <h2 class='white bold'>Submit</h2>
            </button>"
]);
?>

<script>
    $(function(){
        $('#submit').click(function(){
            var input = $('.element_id').val();
            console.log('input: ', input);

            if(empty(input))
            {
                swal
                (
                    {
                        title:"Error!",
                        text:"Please choose an Element",
                        type:"error",
                        confirmButtonColor:"#5890ff",
                        confirmButtonText:"Ok"
                    }
                );

                return false;
            }

            ajax
            (
                '/php/portal',
                {
                    'run':'admin/kek/add_element_to_page',
                    'page_id':'<?php echo $_REQUEST['id']?>',
                    'element_id':input,
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
                            confirmButtonText:"Leave",
                            cancelButtonColor:"#5890ff",
                            cancelButtonText:"Stay",
                            showCancelButton:true,
                        },
                        function(){
                            location.href = '/admin/kek/pages/view?id=<?php echo $_REQUEST['id']?>'
                        }
                    );
                }
            );
        });
    });
</script>