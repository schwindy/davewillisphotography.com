<?php
acl_require_admin(CURRENT_PATH);

$row = $db->get_row("SELECT * FROM orders WHERE id='$_REQUEST[id]'");
if (empty($row)) {
    redirect_to('/admin/sales/');
}

$sale = new Sale($row);

echo __html('card', [
    'class' => 'text_left',
    'text'  => __html('h1',
            ['text' => "Order: " . $sale->id, 'prop' => ['class' => 'text_left']]) . __html('br') . __html('p',
            '<b>Customer Name: </b>' . $sale->customer_name) . __html('p', '<b>Type: </b>' . $sale->type) . __html('p',
            '<b>Amount: </b>' . $sale->cart_total_human) . __html('p', '<b>Created: </b>' . $sale->created)
]);

echo generate_order_cart($sale->cart);