<?php

function change_username()
{
    if (empty($_REQUEST['username'])) {
        new APIResponse(0, "Missing username");
    }
    if (str_contains($_REQUEST['username'], '@')) {
        new APIResponse(0, "Username cannot contain the @ symbol!");
    }

    $db = Database::getInstance();

    $user = $db->get_row("SELECT * FROM users WHERE id='$_REQUEST[id]'");
    if (empty($user)) {
        new APIResponse(0, "Invalid ID");
    }

    $match = $db->get_row("SELECT * FROM users WHERE username='$_REQUEST[username]'");
    if (!empty($match)) {
        new APIResponse(0, "Username is already in use!");
    }

    $db->update('users', [
        'username' => $_REQUEST['username'],
    ], [
        'id' => $_REQUEST['id'],
    ]);

    new APIResponse(1, "Username changed successfully!");
}