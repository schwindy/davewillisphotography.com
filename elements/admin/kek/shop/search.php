<?php
acl_require_admin(CURRENT_PATH);

$search = __search([
    "table_name" => "elements",
    "class_name" => "Element",
    "page_path"  => "/admin/kek/shop/search",
    "order_by"   => "display_name ASC",
    "where"      => "type='shop_item'",
    "fields"     => [
        "display_name" => 3,
        "data"         => 3,
        "notes"        => 2,
        "type"         => 1,
    ],
]);

echo __html('card', [
    'text' => __html('h1', ['text' => 'Search']) . __html('search')
]);

echo __html('table', [
    'title'    => 'Elements',
    'elements' => $search['elements'],
    'headers'  => [
        'Name'    => 'button width_5',
        'Type'    => 'button width_5',
        'Updated' => 'button width_5',
        'Manage'  => 'button width_5',
    ],
    'fields'   => [
        'display_name' => '',
        'type'         => '',
        'updated'      => '',
        'button'       => [
            'class'  => 'button blue_bg white',
            'href'   => '/admin/kek/shop/view?id=$id',
            'tokens' => [
                '$id' => 'id'
            ]
        ]
    ],
]);

echo $search['paginator_html'];