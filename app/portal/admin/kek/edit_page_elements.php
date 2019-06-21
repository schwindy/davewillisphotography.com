<?php

function edit_page_elements()
{
    if (empty($_REQUEST['id'])) {
        new APIResponse(0, "Missing id");
    }

    $db = Database::getInstance();
    $page = new Page($db->get_row("SELECT * FROM pages WHERE id='$_REQUEST[id]'"));
    $page->body['elements'] = $_REQUEST['elements'];

    $db->update('pages', [
        'body' => json_encode($page->body)
    ], [
        'id' => $_REQUEST['id']
    ]);

    new APIResponse(1, "Elements changed successfully!");
}