<?php

function subscription_create()
{
    if (empty($_REQUEST['user_id'])) {
        new APIResponse(0, "Missing user_id");
    }

    if (empty($_REQUEST['payment_method_nonce'])) {
        new APIResponse(0, "Missing payment_method_nonce");
    }

    if (empty($_REQUEST['plan_id'])) {
        new APIResponse(0, "Missing plan_id");
    }

    $user = get_user($_REQUEST['user_id']);
    if (empty($user)) {
        new APIResponse(0, "Invalid user_id");
    }

    $result = braintree_subscription_create([
        'paymentMethodNonce' => $_REQUEST['payment_method_nonce'],
        'planId'             => $_REQUEST['plan_id'],
        'user_id'            => $user['id'],
    ]);

    if ($result->error) {
        new APIResponse(0, "An error occurred while creating a Braintree Subscription...", $result);
    }

    $message = "<html>
					<head>
						<title>" . SITE_NAME . " Subscription</title>
					</head>
					<body>
						<h1 style=\"color: #000;\">Your " . SITE_NAME . " Subscription has just been activated!</h1>
						<p style=\"color: #000; font-size: 12pt;\">We hope you enjoy " . SITE_NAME . ".</p>
					</body>
				</html>";
    send_email($user['email'], SITE_NAME . " Subscription", $message);

    new APIResponse(1, "Subscription created successfully!", $result);
}