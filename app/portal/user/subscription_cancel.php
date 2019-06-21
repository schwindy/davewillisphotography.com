<?php

function subscription_cancel()
{
    $db = Database::getInstance();

    if (empty($_REQUEST['user_id'])) {
        new APIResponse(0, "Missing user_id");
    }

    $user = get_user($_REQUEST['user_id']);
    if (empty($user)) {
        new APIResponse(0, "Invalid user_id");
    }

    if (empty($user['subscription_id'])) {
        new APIResponse(0, "No active subscriptions found...");
    }

    $result = braintree_subscription_cancel($user['subscription_id']);
    if ($result->error) {
        new APIResponse(0, "An error occurred while cancelling a Braintree Subscription...", $result);
    }

    $db->update('users', [
        'account_type'        => 'Free',
        'subscription_end'    => $user['subscription_renew'],
        'subscription_status' => 'inactive',
    ], [
        'id' => $user['id']
    ]);

    $message = "<html>
					<head>
						<title>" . SITE_NAME . " Subscription Cancellation</title>
					</head>
					<body>
						<h1 style=\"color: #000;\">Your " . SITE_NAME . " Subscription has been cancelled. We are sorry to see you go.</h1>
						<p style=\"color: #000; font-size: 12pt;\">If you did not make this change, please contact Support immediately.</p>
					</body>
				</html>";
    send_email($user['email'], SITE_NAME . " Subscription Cancellation", $message);

    new APIResponse(1, "Your changes have been implemented!");
}