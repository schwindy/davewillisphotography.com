<?php

function change_display_name()
{
    if (empty($_REQUEST['display_name'])) {
        new APIResponse(0, "Missing display name");
    }

    $db = Database::getInstance();

    $user = $db->get_row("SELECT * FROM users WHERE id='$_REQUEST[id]'");
    if (empty($user)) {
        new APIResponse(0, "Invalid ID");
    }

    $db->update('users', [
        'display_name' => $_REQUEST['display_name'],
    ], [
        'id' => $_REQUEST['id'],
    ]);

    new APIResponse(1, "Name changed successfully!");
}