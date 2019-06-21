<?php
// @SEE: ./app/lib/__html.php
echo __html('table', [
    'title'    => 'Templates',
    'elements' => $templates,
    'headers'  => [
        'Name'        => 'button width_5',
        'Notes'       => 'button width_5',
        'Last Update' => 'button width_5',
        'Manage'      => 'button width_5',
    ],
    'fields'   => [
        'display_name' => '',
        'notes'        => '',
        'updated'      => '',
        'button'       => [
            'class'  => 'button blue_bg white',
            'href'   => '/admin/support/template_reply?id=$id',
            'tokens' => [
                '$id' => 'id'
            ]
        ]
    ],
]);