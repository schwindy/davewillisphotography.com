<?php
if (!empty($_REQUEST['redirect'])) {
    redirect_to($_REQUEST['redirect']);
}

echo __html('card', [
    'class' => 'text_center',
    'text'  => __html('h1', COMPANY_NAME . " Admin Portal")
]);

echo __html('menu', [
    'title'   => 'kekPHP Menu',
    'buttons' => [
        'Configuration' => [
            'href' => '/admin/kek/config',
            'text' => 'Manage Configuration',
        ],
        'Elements'      => [
            'href' => '/admin/kek/elements',
            'text' => 'Manage Elements',
        ],
        'Pages'         => [
            'href' => '/admin/kek/pages',
            'text' => 'Manage Pages',
        ],
        'Sales'         => [
            'href' => '/admin/sales',
            'text' => 'Analyze Sales',
        ],
    ],
]);

echo __html('card', [
    'class' => 'text_center',
    'text'  => __html('h2', "User Statistics")
        . __html('p', "<b>" . $db->count("users") . "</b> Total Users")
        . __html('p', "<b>" . $db->count("users", "account_type='admin'") . "</b> Admin Users")
        . __html('p', "<b>" . $db->count("users", "account_type='free'") . "</b> Free Users")
        . __html('p', "<b>" . $db->count("users", "NOT account_type='free' AND NOT account_type='admin'") . "</b> Paid Users")
        . __html('br')
        . __html('h2', "Usage Statistics")
        . __html('p', "<b>" . $db->count("jobs") . "</b> Jobs")
]);