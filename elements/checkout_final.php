<?php
if(!empty($_REQUEST["payment_method_nonce"]))
{
    echo
    "<script>
        ajax
        (
            '/php/portal',
            {
                'run':'user/checkout_final',
                'payment_method_nonce':'$_REQUEST[payment_method_nonce]',
                'cart':get_cart(),
                'customer_name':localStorage.customer_name,
                'customer_email':localStorage.customer_email,
                'customer_phone':localStorage.customer_phone,
                'address':localStorage.address,
                'city':localStorage.city,
                'state':localStorage.state,
                'zip':localStorage.zip,
            },
            function(response)
            {
                if(!response.status)
                {
                    swal
                    (
                        {
                            title: 'Error!',
                            text: response.message,
                            type: 'error',
                            confirmButtonColor: '#5890ff',
                            confirmButtonText: 'Ok'
                        },
                        function()
                        {
                            location.href = '/cart';
                        }
                    );
                    return false;
                }
                
                reset_cart();
                $('.braintree_wrapper').css('display', 'none');
                swal
                (
                    {
                        title: 'Order Created Successfully!',
                        text: 'Thank you for your business! You will receive a confirmation email shortly.',
                        type: 'success',
                        confirmButtonColor: '#5890ff',
                        confirmButtonText: 'Ok'
                    },
                    function()
                    {
                        location.href = '/';
                    }
                )
            }
        )
    </script>";
}
?>

<section class="braintree_wrapper card">
    <section class="__splash">
        <div class="__splash_logo">
            <img class="width_50" src="/img/404.png">
        </div>
        <h2 class="bold"><?php echo SITE_NAME." - ".COMPANY_NAME_LONG?></h2>
        <br>
        <h1 class="text_center">Checkout</h1>
        <br>
        <p class="font_lg bold">Total: $<span id="total"></span></p>
    </section>

    <form id="checkout" method="post" action="/checkout_final">
        <div id="payment-form"></div>
        <input type="hidden" id="amount" name="amount">
        <input type="hidden" id="item" name="item" value="<?php echo COMPANY_NAME?> Shop">

        <input
            class="width_100 button theme_color margin_md_top border_0 padding_md_y font_lg bold"
            type="submit"
            value="Go"
        >
    </form>
</section>

<script>
    $(function()
    {
        braintree.setup
        (
            "<?php echo Braintree\ClientToken::generate();?>",
            "dropin",
            {
                container:"payment-form"
            }
        )

        ajax
        (
            PATH_PORTAL,
            {
                'run':'user/calculate_cart_total',
                'cart':get_cart(),
            },
            function(response)
            {
                if(empty(response.data))
                {
                    swal
                    (
                        {
                            title: "Your cart is empty!",
                            text: "Visit the Shop to add items to your cart.",
                            type: "warning",
                            confirmButtonColor: "#5890ff",
                            confirmButtonText: "Ok"
                        },
                        function()
                        {
                            location.href = '/shop';
                        }
                    );
                    return false;
                }

                $('#total').html(response.data.toFixed(2));
            }
        )
    })
</script>