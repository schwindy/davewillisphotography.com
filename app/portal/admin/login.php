<?php

function login()
{
    if (empty($_REQUEST['username'])) {
        new APIResponse(0, "Missing username");
    }
    if (empty($_REQUEST['password'])) {
        new APIResponse(0, "Missing password");
    }

    $db = Database::getInstance();
    $hash = sha1(PJSalt . $_REQUEST['password']);
    $user = $db->get_row("SELECT * 
        FROM users 
        WHERE 
          (username='$_REQUEST[username]' OR email='$_REQUEST[username]') 
          AND password='$hash'
          AND type='admin'
          AND account_type='admin'");

    if (empty($user)) {
        new APIResponse(0, "User not found!", $hash);
    }

    $db->update('users', [
        'last_login' => time(),
        'token'      => null,
    ], [
        'id' => $user['id']
    ]);

    session_login($user['id']);
    new APIResponse(1, "Success", $user);
}