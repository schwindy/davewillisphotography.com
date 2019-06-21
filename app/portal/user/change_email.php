<?php

function change_email()
{
    if (empty($_REQUEST['email'])) {
        new APIResponse(0, "Missing email");
    }

    $db = Database::getInstance();

    $user = $db->get_row("SELECT * FROM users WHERE id='$_REQUEST[id]'");
    if (empty($user)) {
        new APIResponse(0, "Invalid id");
    }

    // Email the user
    $message = "<html>
            <head>
                <title>" . SITE_NAME . " Account Change</title>
            </head>
            <body>
                <h1 style=\"color: #000;\">Your " . SITE_NAME . " Account Email Address has just been updated!</h1>
                <p style=\"color: #000; font-size: 12pt;\">New Email Address: $_REQUEST[email]</p>
            </body>
        </html>";

    // Send email to old email
    send_email($user['email'], SITE_NAME . " | Account Change", $message);

    // Send email to new email
    send_email($_REQUEST['email'], SITE_NAME . " | Account Change", $message);

    $db->update('users', [
        'email' => $_REQUEST['email'],
    ], [
        'id' => $_REQUEST['id'],
    ]);

    new APIResponse(1, "Email changed successfully!");
}