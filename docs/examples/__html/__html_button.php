<?php
// @SEE: ./app/lib/__html.php
echo __html('card', [
    'text' => __html('button', [
        'class' => '',
        'color' => 'blue',
        'icon'  => 'add',
        'text'  => 'Create New Part',
        'href'  => '/admin/inventory/parts/create',
    ])
]);