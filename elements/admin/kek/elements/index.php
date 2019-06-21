<?php
echo __html('card', [
    'class' => 'text_center',
    'text'  => __html('h1', "Elements") . __html('search', ['action' => '/admin/kek/elements/search'])
]);

echo __html('menu', [
    'buttons' => [
        'Create' => [
            'href' => '/admin/kek/elements/create',
            'text' => 'Create a new Element',
        ],
        'Search' => [
            'href' => '/admin/kek/elements/search',
            'text' => 'Search All Elements',
        ],
        'Menus'  => [
            'href' => '/admin/kek/elements/search?q=menu',
            'text' => 'Manage Menu Elements',
        ],
        'Views'  => [
            'href' => '/admin/kek/elements/search?q=view',
            'text' => 'Manage View Elements',
        ],
    ],
]);

echo __html('table', [
    'title'    => 'Elements',
    'elements' => $db->get_rows("SELECT * FROM elements WHERE NOT type='shop_item' ORDER BY display_name ASC"),
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
?>
<script>$(function(){
        $('title').html($('title').html().replace('Index', 'Elements'));
    })</script>