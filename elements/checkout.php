<?php

echo __html
(
    'form',
    [
        'title'=>'Checkout Information',
        'fields'=>
        [
            'customer_name'=>
            [
                'placeholder'=>'Name',
            ],
            'customer_email'=>
            [
                'placeholder'=>'Email',
            ],
            'customer_phone'=>
            [
                'placeholder'=>'Phone # (xxx-xxx-xxxx)',
            ],
            'address'=>
            [
                'placeholder'=>'Address',
            ],
            'city'=>
            [
                'placeholder'=>'City',
            ],
            'state'=>
            [
                'placeholder'=>'State',
            ],
            'zip'=>
            [
                'placeholder'=>'ZIP',
            ],
        ],
    ]
);
?>

<script>
    $(function()
    {
        $('#customer_name').val(localStorage.customer_name);
        $('#customer_email').val(localStorage.customer_email);
        $('#customer_phone').val(localStorage.customer_phone);
        $('#address').val(localStorage.address);
        $('#city').val(localStorage.city);
        $('#state').val(localStorage.state);
        $('#zip').val(localStorage.zip);

        $('.kek_form').children('button').click(function()
        {
            if(empty($('#customer_name').val()))
            {
                swal
                (
                    {
                        title: 'Error!',
                        text: "Please enter your Name",
                        type: 'error',
                        confirmButtonColor: '#5890ff',
                        confirmButtonText: 'Ok'
                    }
                );
                return false;
            }

            if(empty($('#customer_email').val()))
            {
                swal
                (
                    {
                        title: 'Error!',
                        text: "Please enter your Email Address",
                        type: 'error',
                        confirmButtonColor: '#5890ff',
                        confirmButtonText: 'Ok'
                    }
                );
                return false;
            }

            if(empty($('#customer_phone').val()))
            {
                swal
                (
                    {
                        title: 'Error!',
                        text: "Please enter your Phone #",
                        type: 'error',
                        confirmButtonColor: '#5890ff',
                        confirmButtonText: 'Ok'
                    }
                );
                return false;
            }

            if(empty($('#address').val()))
            {
                swal
                (
                    {
                        title: 'Error!',
                        text: "Please enter your Address",
                        type: 'error',
                        confirmButtonColor: '#5890ff',
                        confirmButtonText: 'Ok'
                    }
                );
                return false;
            }

            if(empty($('#city').val()))
            {
                swal
                (
                    {
                        title: 'Error!',
                        text: "Please enter your City",
                        type: 'error',
                        confirmButtonColor: '#5890ff',
                        confirmButtonText: 'Ok'
                    }
                );
                return false;
            }

            if(empty($('#state').val()))
            {
                swal
                (
                    {
                        title: 'Error!',
                        text: "Please enter your State",
                        type: 'error',
                        confirmButtonColor: '#5890ff',
                        confirmButtonText: 'Ok'
                    }
                );
                return false;
            }

            if(empty($('#zip').val()))
            {
                swal
                (
                    {
                        title: 'Error!',
                        text: "Please enter your Zip",
                        type: 'error',
                        confirmButtonColor: '#5890ff',
                        confirmButtonText: 'Ok'
                    }
                );
                return false;
            }

            localStorage.customer_name = $('#customer_name').val();
            localStorage.customer_email = $('#customer_email').val();
            localStorage.customer_phone = $('#customer_phone').val();
            localStorage.address = $('#address').val();
            localStorage.city = $('#city').val();
            localStorage.state = $('#state').val();
            localStorage.zip = $('#zip').val();
            location.href = '/checkout_final';
            return false;
        });
    });
</script>