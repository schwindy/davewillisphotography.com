<?php

function add_element_to_page()
{
    if (empty($_REQUEST['page_id'])) {
        new APIResponse(0, "Missing page_id");
    }
    if (empty($_REQUEST['element_id'])) {
        new APIResponse(0, "Missing element_id");
    }

    $db = Database::getInstance();
    $page = new Page($db->get_row("SELECT * FROM pages WHERE id='$_REQUEST[page_id]'"));
    $page->body['elements'][] = $_REQUEST['element_id'];

    $db->update('pages', [
        'body' => json_encode($page->body)
    ], [
        'id' => $_REQUEST['page_id']
    ]);

    new APIResponse(1, "Element added successfully!");
}