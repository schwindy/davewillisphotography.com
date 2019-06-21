<?php

function create_template()
{
    if (empty($_REQUEST['type'])) {
        return false;
    }

    if (empty($_REQUEST['display_name'])) {
        return false;
    }

    if (empty($_REQUEST['data'])) {
        return false;
    }

    $db = Database::getInstance();

    $_REQUEST['data'] = nl2br($_REQUEST['data']);
    $_REQUEST['notes'] = nl2br($_REQUEST['notes']);

    $db->insert("templates", [
        'id'           => generate_mysql_id(),
        'type'         => $_REQUEST['type'],
        'display_name' => $_REQUEST['display_name'],
        'data'         => $_REQUEST['data'],
        'notes'        => $_REQUEST['notes'],
    ]);

    return true;
}