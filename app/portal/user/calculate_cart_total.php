<?php

function calculate_cart_total()
{
    if (empty($_REQUEST['cart'])) {
        new APIResponse(0, "Missing cart");
    }
    new APIResponse(1, "Cart total calculated successfully!", get_cart_total());
}