<?php

function get_cart_html()
{
    if (empty($_REQUEST['data'])) {
        new APIResponse(0, "Missing data");
    }
    new APIResponse(1, "Success", generate_cart_element($_REQUEST['data']));
}