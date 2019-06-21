<?php
if (!empty($_REQUEST['path'])) {
    create_page();
}

echo __html('form', [
    'title'  => 'Create Page',
    'fields' => [
        'display_name' => [
            'placeholder' => 'Page Name',
        ],
        'title'        => [
            'placeholder' => 'Page Title',
        ],
        'description'  => [
            'placeholder' => 'Page Description (ex: Create a free account)',
        ],
        'path'         => [
            'placeholder' => 'Page Path (ex: /index)',
        ],
        'object_class' => [
            'placeholder' => 'Object Class (ex: Element)',
        ],
        'object_table' => [
            'placeholder' => 'Object Table (ex: elements)',
        ],
        'type'         => [
            'placeholder' => 'Page Type',
        ],
        'head'         => [
            'placeholder' => 'Page Head (ex: head)',
        ],
        'nav'          => [
            'placeholder' => 'Page Nav (ex: nav)',
        ],
        'foot'         => [
            'placeholder' => 'Page Foot (ex: foot)',
        ],
        'notes'        => [
            'placeholder' => 'Page Notes (Admins Only)',
        ],
    ],
]);