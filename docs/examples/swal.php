<script>
    // Standard Alert
    swal
    (
        {
            title:"Error!",
            text:"Invalid Phone Number",
            type:"error",
            confirmButtonColor:"#5890ff",
            confirmButtonText:"Ok"
        }
    );

    // Standard Alert with a Confirm Callback
    swal
    (
        {
            title:"Error!",
            text:"This element does not exist...",
            type:"error",
            confirmButtonColor:"#5890ff",
            confirmButtonText:"Ok"
        },
        function(){
            location.href = '/';
        }
    );

    // Standard Alert with Confirm and Cancel Callbacks
    swal
    (
        {
            title:"Item Added to Cart!",
            text:"Would you like to Checkout or Continue Shopping?",
            type:"success",
            confirmButtonColor:"#5890ff",
            confirmButtonText:"Checkout",
            cancelButtonText:"Continue Shopping",
            showCancelButton:true,
        },
        function(){
            // Confirm Callback
        },
        function(){
            // Cancel Callback
        }
    );

    // Standard Alert that halts execution of its parent function if a single condition is met
    // Example: Require that $('#invite_code).val() is not empty. If empty, return false after showing alert
    $('#submit_invite').click(function(){
        if(empty($('#invite_code').val()))
        {
            swal
            (
                {
                    title:'Error!',
                    text:'Please input your Invite Code.',
                    type:'error',
                    confirmButtonColor:'#5890ff',
                    confirmButtonText:'Ok'
                }
            );
            return false;
        }

        location.href = '/dealers/signup?invite='+$('#invite_code').val();
        return false;
    });
</script>