<?php

echo __html('card', [
    'class' => 'text_center',
    'text'  => __html('h1', "kekPHP Home") . __html('p', "Click any of the buttons below to get started!") . __html('p',
            "<b>Tip:</b> CTRL+Click any button to open it in a new tab.")
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
?>
<script>$(function(){
        $('title').html($('title').html().replace('Index', 'kekPHP'));
    })</script>