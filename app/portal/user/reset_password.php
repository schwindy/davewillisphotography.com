<?php

function reset_password()
{
    if (empty($_REQUEST['email'])) {
        new APIResponse(0, "Missing email");
    }

    $db = Database::getInstance();

    $user = $db->get_row("SELECT * FROM users WHERE email='$_REQUEST[email]'");
    if (empty($user)) {
        new APIResponse(0, "Account does not exist!");
    }

    $password = generateCryptoCode(8);
    $temp_password = sha1(PJSalt . $password);
    $db->query("UPDATE users SET temp_password='$temp_password' WHERE email='$_REQUEST[email]';");

    $message = "<html>
					<head>
						<title>" . SITE_NAME . " Password Reset</title>
					</head>
					<body>
						<h1 style=\"color: #000;\">Your password has been reset</h1>
						<p style=\"color: #000; font-size: 12pt;\">Your temporary password is: $password</p>
					</body>
				</html>";
    send_email($_REQUEST['email'], SITE_NAME . ' Temporary Password', $message);
    new APIResponse(1, "Password reset successfully!");
}