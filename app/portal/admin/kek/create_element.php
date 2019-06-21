<?php

function create_element()
{
    if (empty($_REQUEST['display_name'])) {
        new APIResponse(0, "Missing display_name");
    }

    $db = Database::getInstance();
    $_REQUEST['id'] = empty($_REQUEST['id']) ? generate_mysql_id() : $_REQUEST['id'];
    $_REQUEST['type'] = empty($_REQUEST['type']) ? 'kek' : $_REQUEST['type'];

    $db->insert("elements", [
        'id'           => $_REQUEST['id'],
        'display_name' => $_REQUEST['display_name'],
        'type'         => $_REQUEST['type'],
        'data'         => $_REQUEST['data'],
        'options'      => json_encode($_REQUEST['options']),
        'notes'        => $_REQUEST['notes'],
        'created'      => get_date(null, 'Y-m-d H:i:s'),
    ]);

    new APIResponse(1, "Element created successfully!", $_REQUEST['id']);
}