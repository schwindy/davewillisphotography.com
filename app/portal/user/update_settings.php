<?php

function update_settings()
{
    $db = Database::getInstance();

    if (empty($_REQUEST['settings'])) {
        new APIResponse(0, "Missing settings");
    }

    $user = $db->get_row("SELECT * FROM users WHERE id='$_REQUEST[id]'");
    if (empty($user)) {
        new APIResponse(0, "Invalid ID");
    }

    $db->update('users', [
        'settings' => json_encode($_REQUEST['settings']),
    ], [
        'id' => $_REQUEST['id'],
    ]);

    new APIResponse(1, "Settings updated successfully!");
}