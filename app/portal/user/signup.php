<?php

function signup()
{
    if (empty($_REQUEST['username']) && empty($_REQUEST['dry_run'])) {
        new APIResponse(0, "Missing username");
    }
    if (str_contains($_REQUEST['username'], '@')) {
        new APIResponse(0, "Username cannot contain the @ symbol!");
    }
    if (empty($_REQUEST['email'])) {
        new APIResponse(0, "Missing email");
    }
    if (empty($_REQUEST['password'])) {
        new APIResponse(0, "Missing password");
    }
    if (empty($_REQUEST['display_name'])) {
        new APIResponse(0, "Missing display_name");
    }
    if (empty($_REQUEST['account_type'])) {
        new APIResponse(0, "Missing account_type");
    }
    if ($_REQUEST['account_type'] === "admin") {
        new APIResponse(0, "Invalid Account Type");
    }

    $db = Database::getInstance();

    $user = $db->get_row("SELECT * FROM users WHERE email='$_REQUEST[email]';");
    if (!empty($user)) {
        new APIResponse(0, "Email is already in use!");
    }

    if (!empty($_REQUEST['dry_run'])) {
        new APIResponse(1, "Signup dry_run completed successfully!");
    }

    $user = $db->get_row("SELECT * FROM users WHERE username='$_REQUEST[username]';");
    if (!empty($user)) {
        new APIResponse(0, "Username is already in use!");
    }

    $db->insert('users', [
        'id'                  => generate_mysql_id(),
        'username'            => $_REQUEST['username'],
        'email'               => $_REQUEST['email'],
        'password'            => sha1(PJSalt . $_REQUEST['password']),
        'display_name'        => $_REQUEST['display_name'],
        'account_type'        => $_REQUEST['account_type'],
        'subscription_status' => $_REQUEST['account_type'] === "free" ? "active" : "inactive",
    ]);

    $message = "<html>
        <head>
            <title>" . SITE_NAME . " Signup</title>
        </head>
        <body>
            <h1 style=\"color: #000;\">Thank you for creating a " . SITE_NAME . " Account!</h1>
            <p style=\"color: #000; font-size: 12pt;\">Your account is now active and you can login by visiting 
                <a href=\"" . SITE_URL . "login\">" . SITE_URL_NAME . "</a>
            </p>
        </body>
    </html>";
    //send_email($_REQUEST['email'], SITE_NAME." Signup", $message);

    new APIResponse(1, "Your account has been successfully created!");
}