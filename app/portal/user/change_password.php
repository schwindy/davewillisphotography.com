<?php

function change_password()
{
    if (empty($_REQUEST['password'])) {
        new APIResponse(0, "Missing password");
    }

    $db = Database::getInstance();

    $user = $db->get_row("SELECT * FROM users WHERE id='$_REQUEST[id]'");
    if (empty($user)) {
        new APIResponse(0, "Invalid ID");
    }

    $db->update('users', [
        'password' => sha1(PJSalt . $_REQUEST['password']),
    ], [
        'id' => $_REQUEST['id'],
    ]);

    new APIResponse(1, "Password changed successfully!");
}