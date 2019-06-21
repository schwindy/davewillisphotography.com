<?php
echo __html('form', [
    'title'    => "Contact Us",
    'subtitle' => "support@refracted.consulting",
    'fields'   => [
        'customer_name'  => [
            'placeholder' => 'Your Name',
        ],
        'customer_email' => [
            'placeholder' => 'Your Email',
        ],
        'subject'        => [
            'placeholder' => 'Subject',
        ],
        'message'        => [
            'placeholder' => 'Message',
        ],
    ],
]);
?>
<script>
    $(function(){
        $('form').on('submit', function(e){
            e.preventDefault();

            if(empty($('#customer_name').val()))
            {
                __alert("Please enter your name!");
                return false;
            }

            if(empty($('#customer_email').val()))
            {
                __alert("Please enter a Description!");
                return false;
            }

            if(empty($('#subject').val()))
            {
                __alert("Please enter the subject of your contact submission!");
                return false;
            }

            if(empty($('#message').val()))
            {
                __alert("Please enter your message!");
                return false;
            }

            ajax
            (
                '/php/portal',
                {
                    'run':'user/submit_contact',
                    'customer_name':$('#customer_name').val(),
                    'customer_email':$('#customer_email').val(),
                    'subject':$('#subject').val(),
                    'message':$('#message').val(),
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
                            location.href = '/';
                        }
                    );
                },
                function(){
                    __alert("An unknown error occurred!");
                }
            )
        })
    })
</script>
