<?php
echo __html('card', [
    'class' => 'text_center',
    'text'  => __html('h1', "Pages") . __html('search', ['action' => '/admin/kek/pages/search'])
]);

echo __html('menu', [
    'buttons' => [
        'Create' => [
            'href' => '/admin/kek/pages/create',
            'text' => 'Create a new Page',
        ],
        'Search' => [
            'href' => '/admin/kek/pages/search',
            'text' => 'Search All Pages',
        ],
        'Admin'  => [
            'href' => '/admin/kek/pages/search?q=admin',
            'text' => 'Manage Admin Pages',
        ],
        'User'   => [
            'href' => '/admin/kek/pages/search?q=user',
            'text' => 'Manage User Pages',
        ],
    ],
]);

echo __html('table', [
    'elements' => $db->get_rows("SELECT * FROM pages ORDER BY path ASC"),
    'headers'  => [
        'Path'    => '',
        'Name'    => '',
        'Updated' => '',
        'Manage'  => '',
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
]);
?>
<script>$(function(){
        $('title').html($('title').html().replace('Index', 'Pages'));
    })</script>