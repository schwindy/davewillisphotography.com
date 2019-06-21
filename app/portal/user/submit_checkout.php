<?php

function submit_checkout()
{
    if (empty($_REQUEST['cart'])) {
        new APIResponse(0, "Missing cart");
    }

    new APIResponse(1, "Success", generate_cart_element($_REQUEST['data']));
}