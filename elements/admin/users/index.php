<?php
$search = __search([
    "table_name" => "users",
    "class_name" => "User",
    "ps"         => 100,
    "order_by"   => "created DESC",
    "fields"     => [
        "username"     => 5,
        "email"        => 4,
        "display_name" => 3,
        "account_type" => 2,
        "phone_number" => 1,
    ],
]);

echo __html('card', [
    'class' => 'text_center',
    'text'  => __html('h1', COMPANY_NAME . " Users") . __html('search')
]);

echo __html('menu', [
    'title'   => 'Users Menu',
    'buttons' => [
        'Free' => [
            'href' => '/admin/users/index?q=Free',
            'text' => 'Manage Free Users',
        ],
        'Paid' => [
            'href' => '/admin/users/index?q=Standard',
            'text' => 'Manage Paid Users',
        ],
    ],
]);

echo empty($search['elements']) ? $search['html'] : __html('table', [
    'elements' => $search['elements'],
    'headers'  => [
        'Username'     => '',
        'Account Type' => '',
        'Display Name' => 'media_800_invisible',
        'Last Login'   => '',
        'Created'      => '',
        'Manage'       => 'print_invisible',
    ],
    'fields'   => [
        'username'     => ['class' => 'bold'],
        'account_type' => [],
        'display_name' => ['class' => 'media_800_invisible'],
        'last_login'   => [],
        'created'      => [],
        'button'       => [
            'class'  => 'button blue_bg white print_invisible',
            'href'   => '/admin/users/view?id=$id',
            'tokens' => ['$id' => 'id']
        ]
    ],
]), $search['paginator_html'];