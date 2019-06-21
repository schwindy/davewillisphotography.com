<?php
acl_require_admin(CURRENT_PATH);

$search = __search([
    "table_name" => "orders",
    "class_name" => "Order",
    "order_by"   => "created DESC",
]);

echo __html('card', [
    'text' => __html('h1', COMPANY_NAME . " Sales") . __html('search')
]);

echo empty($search['elements']) ? $search['html'] : __html('table', [
    'elements' => $search['elements'],
    'headers'  => [
        'Customer Name' => '',
        'Type'          => '',
        'Amount'        => '',
        'Created'       => '',
        'Details'       => 'print_invisible',
    ],
    'fields'   => [
        'customer_name'    => [
            'class' => 'bold',
        ],
        'type'             => [],
        'cart_total_human' => [],
        'created'          => [],
        'button'           => [
            'class'  => 'button blue_bg white print_invisible',
            'href'   => '/admin/sales/view?id=$id',
            'tokens' => ['$id' => 'id']
        ]
    ],
]), $search['paginator_html'];