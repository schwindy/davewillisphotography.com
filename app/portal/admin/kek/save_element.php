<?php

function save_element()
{
    if (empty($_REQUEST['display_name'])) {
        new APIResponse(0, "Missing display_name");
    }

    $db = Database::getInstance();
    $_REQUEST['id'] = empty($_REQUEST['id']) ? generate_mysql_id() : $_REQUEST['id'];
    $_REQUEST['type'] = empty($_REQUEST['type']) ? 'kek' : $_REQUEST['type'];

    $condition = strpos($_REQUEST['type'], "_item") === false;
    $_REQUEST['path'] = $condition ? 'elements' : str_replace("_item", "", $_REQUEST['type']);

    $db->update_or_insert("elements", [
        'id'           => $_REQUEST['id'],
        'display_name' => $_REQUEST['display_name'],
        'type'         => $_REQUEST['type'],
        'data'         => $_REQUEST['data'],
        'options'      => empty($_REQUEST['options']) ? null : json_encode($_REQUEST['options']),
        'properties'   => empty($_REQUEST['properties']) ? null : json_encode($_REQUEST['properties']),
        'notes'        => $_REQUEST['notes'],
    ]);

    new APIResponse
    (1, "Element saved successfully!", [
        'id'   => $_REQUEST['id'],
        'path' => $_REQUEST['path'],
    ]);
}