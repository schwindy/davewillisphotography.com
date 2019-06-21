<?php
if (!empty($_REQUEST['path'])) {
    save_page();
}
$page = $db->get_row("SELECT * FROM pages WHERE id='$_REQUEST[id]'");

echo __html('form', [
    'title'  => 'Edit Page',
    'fields' => [
        'id'           => [
            'type'  => 'hidden',
            'value' => $_REQUEST['id'],
        ],
        'display_name' => [
            'placeholder' => 'Page Name',
            'value'       => $page['display_name'],
        ],
        'title'        => [
            'placeholder' => 'Page Title',
            'value'       => $page['title'],
        ],
        'description'  => [
            'placeholder' => 'Page Description (ex: Create a free account)',
            'value'       => $page['description'],
        ],
        'path'         => [
            'placeholder' => 'Page Path (ex: /index)',
            'value'       => $page['path'],
        ],
        'object_class' => [
            'placeholder' => 'Object Class (ex: Element)',
            'value'       => $page['object_class'],
        ],
        'object_table' => [
            'placeholder' => 'Object Table (ex: elements)',
            'value'       => $page['object_table'],
        ],
        'type'         => [
            'placeholder' => 'Page Type',
            'value'       => $page['type'],
        ],
        'head'         => [
            'placeholder' => 'Page Head (ex: head)',
            'value'       => $page['head'],
        ],
        'nav'          => [
            'placeholder' => 'Page Nav (ex: nav)',
            'value'       => $page['nav'],
        ],
        'foot'         => [
            'placeholder' => 'Page Foot (ex: foot)',
            'value'       => $page['foot'],
        ],
        'notes'        => [
            'placeholder' => 'Page Notes',
            'value'       => $page['notes'],
        ],
    ],
]);