<?php

function change_account_type()
{
    if (empty($_REQUEST['account_type'])) {
        new APIResponse(0, "Missing account_type");
    }

    $db = Database::getInstance();

    $user = $db->get_row("SELECT * FROM users WHERE id='$_REQUEST[id]'");
    if (empty($user)) {
        new APIResponse(0, "Invalid ID");
    }

    $db->update('users', [
        'account_type' => $_REQUEST['account_type'],
    ], [
        'id' => $_REQUEST['id'],
    ]);

    new APIResponse(1, "Account type changed successfully!");
}