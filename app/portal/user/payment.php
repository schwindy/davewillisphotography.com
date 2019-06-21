<?php
function payment()
{
    if (empty($_REQUEST['currency_id'])) {
        new APIResponse(0, "Missing: currency_id");
    }
    if (empty($_REQUEST['plan_id'])) {
        new APIResponse(0, "Missing: plan_id");
    }
    if (empty($_REQUEST['user_id'])) {
        new APIResponse(0, "Missing: user_id");
    }
    $db = Database::getInstance();

    $plan = $db->get_row("SELECT * FROM plans WHERE id='$_REQUEST[plan_id]'");
    if (empty($plan)) {
        new APIResponse(0, "Invalid Plan");
    }

    $payment_method = $db->get_row("SELECT * FROM payment_methods WHERE currency_id='$_REQUEST[currency_id]'");
    if (empty($payment_method)) {
        new APIResponse(0, "Invalid Payment Method");
    }

    $id = generate_mysql_id(32);
    $plan = new Plan($plan);
    if (empty($db->insert('payments', [
        'id'              => $id,
        'cost'            => convert_to("BTC", $plan->cost_btc, $_REQUEST['currency_id']),
        'cost_btc'        => $plan->cost_btc,
        'currency_id'     => $payment_method['currency_id'],
        'deposit_address' => $payment_method['deposit_address'],
        'plan_id'         => $_REQUEST['plan_id'],
        'user_id'         => $_REQUEST['user_id'],
    ]))) {
        new APIResponse(0, "Database Error: Unable to create payment...");
    }

    if (empty($db->update('users', [
        'account_type'        => $plan->id,
        'payment_id'          => $id,
        'subscription_status' => 'pending',
    ], [
        'id' => $_REQUEST['user_id'],
    ]))) {
        new APIResponse(0, "Database Error: Unable to update user...");
    }

    new APIResponse(1, "Payment created successfully", $id);
}