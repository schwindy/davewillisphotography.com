<?php
acl_require_admin(CURRENT_PATH);

echo __html('card', [
    'text' => __html('h1', ['text' => "Shop Management", 'class' => 'text_center']) . __html('search', ['action' => '/admin/kek/shop/search'])
]);

echo __html('menu', [
    'buttons' => [
        'Create' => [
            'href' => '/admin/kek/shop/create',
            'text' => 'Create a New Shop Item',
        ],
        'Search' => [
            'href' => '/admin/kek/shop/search',
            'text' => 'Search All Shop Items',
        ],
    ],
]);

$elements = $db->get_rows("SELECT * FROM elements WHERE type='shop_item' ORDER BY display_name ASC");
if (empty($elements)) {
    $_ALERT[] = ['text' => 'You currently have 0 Shop Items. Click the Create Button to fix that!'];
}

echo __html('table', [
    'title' => 'Shop Items',
    'elements' => $elements,
    'headers' => [
        'Name' => 'button width_5',
        'Type' => 'button width_5',
        'Updated' => 'button width_5',
        'Manage' => 'button width_5',
    ],
    'fields' => [
        'display_name' => '',
        'type' => '',
        'updated' => '',
        'button' => [
            'class' => 'button blue_bg white',
            'href' => '/admin/kek/shop/view?id=$id',
            'tokens' => [
                '$id' => 'id'
            ]
        ]
    ],
]);