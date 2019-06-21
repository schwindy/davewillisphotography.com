<?php
// @SEE: ./app/lib/__html.php
echo __html('menu', [
    'title'   => 'Test Menu :D',
    'buttons' => [
        'Create Reply' => [
            'href' => '/admin/support/template_create?type=reply',
            'text' => 'Create a New Template Reply',
        ],
        'Test Reply'   => [
            'href' => '/admin/support/template_test',
            'text' => 'Test a Template Reply via Staff Email',
        ],
    ],
]);