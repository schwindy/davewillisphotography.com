<?php
$page = $db->get_row("SELECT * FROM pages WHERE id='$_REQUEST[id]'");
if (empty($page)) {
    redirect_to('/admin/kek/pages');
}
echo __html('card', [
    'text' => __html('h1', "Page: $page[display_name]") . __html('br') . __html('p',
            "<b>Object Class:</b> $page[object_class]") . __html('p',
            "<b>Object Table:</b> $page[object_table]") . __html('br') . __html('p',
            "<b>Type:</b> $page[type]") . __html('p', "<b>Path:</b> $page[path]") . __html('p',
            "<b>Title:</b> $page[title]") . __html('p', "<b>Description:</b> $page[description]") . __html('p',
            "<b>Head:</b> $page[head]") . __html('p', "<b>Nav:</b> $page[nav]") . __html('p',
            "<b>Foot:</b> $page[foot]") . __html('p', "<b>Created:</b> $page[created]") . __html('p',
            "<b>Updated:</b> $page[updated]") . __html('p', "<b>Notes:</b> $page[notes]")
]);

echo __html('menu', [
    'buttons' => [
        'Add Element'     => [
            'href' => "/admin/kek/pages/add_element?id=$_REQUEST[id]",
            'text' => 'Add Elements to this Page',
        ],
        'Manage Elements' => [
            'href' => "/admin/kek/pages/elements?id=$_REQUEST[id]",
            'text' => 'Manage Elements on this Page',
        ],
    ],
]);

$page = new Page($page);
$elements = [];
foreach ($page->body['elements'] as $id) {
    $elements[] = $db->get_row("SELECT * FROM elements WHERE id='$id'");
}

echo __html('table', [
    'title'    => 'Page Elements',
    'elements' => $elements,
    'headers'  => [
        'Name'    => 'button',
        'Type'    => 'button',
        'Updated' => 'button',
        'Manage'  => 'button',
    ],
    'fields'   => [
        'display_name' => '',
        'type'         => '',
        'updated'      => '',
        'button'       => [
            'class'  => 'button blue_bg white',
            'href'   => '/admin/kek/elements/view?id=$id',
            'tokens' => ['$id' => 'id']
        ]
    ],
]);
?>
<script>$(function(){
        $('title').html($('title').html().replace('View', 'Page'));
    })</script>