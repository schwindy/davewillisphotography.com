<?php
$search = __search([
    "table_name" => "elements",
    "class_name" => "Element",
    "page_path"  => "/admin/kek/elements/search",
    "order_by"   => "display_name ASC",
    "fields"     => [
        "display_name" => 3,
        "data"         => 3,
        "notes"        => 2,
        "type"         => 1,
    ],
]);

echo __html('card', [
    'class' => 'text_center',
    'text'  => __html('h1', 'Search Elements') . __html('search')
]);

echo __html('menu', [
    'buttons' => [
        'Menus' => [
            'href' => '/admin/kek/elements/search?q=menu',
            'text' => 'Manage Menu Elements',
        ],
        'Views' => [
            'href' => '/admin/kek/elements/search?q=view',
            'text' => 'Manage View Elements',
        ],
    ],
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
        'display_name' => 'text_left padding_sm_left',
        'type'         => '',
        'updated'      => '',
        'button'       => [
            'class'  => 'button blue_bg white',
            'href'   => '/admin/kek/elements/view?id=$id',
            'tokens' => [
                '$id' => 'id'
            ]
        ]
    ],
]);

echo $search['paginator_html'];