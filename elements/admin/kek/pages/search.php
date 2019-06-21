<?php
$search = __search([
    "table_name" => "pages",
    "class_name" => "Page",
    "page_path"  => "/admin/kek/pages/search",
    "order_by"   => "path ASC",
    "fields"     => [
        "path"         => 3,
        "display_name" => 3,
        "title"        => 2,
        "body"         => 2,
        "notes"        => 1,
    ],
]);

echo __html('card', [
    'class' => 'text_center',
    'text'  => __html('h2', "Search Pages") . __html('search')
]);

echo __html('menu', [
    'buttons' => [
        'Admin' => [
            'href' => '/admin/kek/pages/search?q=admin',
            'text' => 'Manage Admin Pages',
        ],
        'User'  => [
            'href' => '/admin/kek/pages/search?q=user',
            'text' => 'Manage User Pages',
        ],
    ],
]);

echo __html('table', [
    'title'    => 'Pages',
    'elements' => $search['elements'],
    'headers'  => [
        'Path'    => 'button width_5',
        'Name'    => 'button width_5',
        'Updated' => 'button width_5',
        'Manage'  => 'button width_5',
    ],
    'fields'   => [
        'path'         => 'text_left padding_sm_left',
        'display_name' => 'text_left padding_sm_left',
        'updated'      => '',
        'button'       => [
            'class'  => 'button blue_bg white',
            'href'   => '/admin/kek/pages/view?id=$id',
            'tokens' => ['$id' => 'id']
        ]
    ],
]), $search['paginator_html'];