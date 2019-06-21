<?php
if (!defined('BRAINTREE_ENVIRONMENT')) {
    return false;
}
require LIB_PATH . 'braintree-php-3.10.0/braintree-php-3.10.0/lib/Braintree.php';
Braintree\Configuration::environment(BRAINTREE_ENVIRONMENT);
Braintree\Configuration::merchantId(BRAINTREE_MERCHANT_ID);
Braintree\Configuration::publicKey(BRAINTREE_PUBLIC_KEY);
Braintree\Configuration::privateKey(BRAINTREE_PRIVATE_KEY);

/** Adds a user from the local database to Braintree as a customer
 * @throws Exception $e
 * @param $user_id
 * @return Braintree_Customer
 */
function braintree_customer_add_from_database($user_id)
{
    $db = Database::getInstance();
    $user = get_user($user_id);
    $customer = braintree_customer_find($user['braintree_customer_id']);
    if (!empty($customer)) {
        return true;
    }

    try {
        $customer_response = Braintree_Customer::create([
            'id'        => $user['id'],
            'email'     => $user['email'],
            'firstName' => $user['full_name'],
        ]);

        if (empty($customer_response->customer)) {
            $message = $customer_response->errors->deepAll()[0]->message;
        }

        $db->update('users', [
            'braintree_customer_id' => $user['id']
        ], [
            'id' => $user['id']
        ]);

        return $customer_response;
    } catch (Exception $e) {

    }
}

/** Find a Braintree customer record using a Braintree customer id
 * @param $customer_id
 * @return bool|Braintree_Customer
 */
function braintree_customer_find($customer_id)
{
    if (empty($customer_id)) {
        return false;
    }

    try {
        $braintree_customer = Braintree_Customer::find($customer_id);
    } catch (Braintree_Exception_NotFound $e) {
        $braintree_customer = false;
    } catch (Exception $e) {
        echo 'Error Retrieving Braintree Customer. Exception: ' . $e->getCode() . ' : ' . $e->getMessage();
        $braintree_customer = false;
    }

    return $braintree_customer;
}

/** Return an array of payment methods associated with a Braintree customer
 * @param $user_id
 * @param null $braintree_customer
 * @return array
 */
function braintree_customer_payment_methods_find($user_id, $braintree_customer = null)
{
    if ($braintree_customer === null) {
        $braintree_customer = braintree_customer_find($user_id);
    }

    return $braintree_customer->paymentMethods;
}

/** Loop through a customers payment methods and aggregate the subscriptions found into one array
 * @param $user_id
 * @param null $payment_methods
 * @param null $customer
 * @return array
 */
function braintree_customer_subscriptions_find($user_id, $payment_methods = null, $customer = null)
{
    if ($payment_methods === null) {
        $payment_methods = braintree_customer_payment_methods_find($user_id, $customer);
    }

    $subscriptions = [];
    foreach ($payment_methods as $payment_method) {
        $subscriptions = $payment_method->subscriptions;
        foreach ($subscriptions as $subscription) {
            $subscriptions[] = $subscription;
        }
    }

    return $subscriptions;
}

/** Echos error messages from a Braintree API call response
 * @param $result
 */
function braintree_display_errors_from_result($result)
{
    foreach ($result->errors->deepAll() as $error) {
        echo($error->code . ": " . $error->message . br() . "\n");
    }
}

/** Returns all of the error messages in a Braintree response as an array of pre-formatted strings
 * @param $result
 * @param array $errors
 * @return array
 */
function braintree_get_errors_from_result($result, $errors = [])
{
    foreach ($result->errors->deepAll() as $error) {
        $errors[] = $error->code . ": " . $error->message . br() . "\n";
    }

    return $errors;
}

/** Create a Sale
 * @param array $args
 * @return bool
 */
function braintree_sale_create($args)
{
    if (empty($args['customer_name'])) {
        $params = [
            'amount'             => $args['amount'],
            'paymentMethodNonce' => $args['paymentMethodNonce'],
            'options'            => ['submitForSettlement' => true]
        ];
    } else {
        $params = [
            'amount'             => $args['amount'],
            'paymentMethodNonce' => $args['paymentMethodNonce'],
            'customer'           => [
                'firstName' => substr($args['customer_name'], 0, strpos($args['customer_name'], " ")),
                'lastName'  => substr($args['customer_name'], strpos($args['customer_name'], " ") + 1),
                'email'     => $args['customer_email'],
                'phone'     => $args['customer_phone'],
            ],
            'options'            => ['submitForSettlement' => true]
        ];
    }

    if (!empty($args['order_id'])) {
        $params['orderId'] = $args['order_id'];
    }

    return Braintree_Transaction::sale($params);
}

/** Sets a subscription to canceled
 * @note: Once cancelled, a subscription cannot be reactivated. Create a new subscription instead.
 * @see https://developers.braintreepayments.com/javascript+php/reference/request/subscription/cancel
 * @param string $subscription_id The value used to identify a specific subscription.
 * @return bool
 */
function braintree_subscription_cancel($subscription_id)
{
    return Braintree_Subscription::cancel($subscription_id);
}

/** Create a Subscription
 * @param array $args
 * @return bool
 */
function braintree_subscription_create($args)
{
    $db = Database::getInstance();

    $result = Braintree_Subscription::create([
        'paymentMethodNonce' => $args['paymentMethodNonce'],
        'planId'             => $args['planId'],
    ]);

    foreach ($result->subscription->nextBillingDate as $key => $val) {
        if ($key === "date") {
            $subscription_renew = $val;
            break;
        }
    }

    $db->update('users', [
        'account_type'        => "Premium",
        'braintree_plan_id'   => $result->subscription->planId,
        'subscription_end'    => $subscription_renew,
        'subscription_id'     => $result->subscription->id,
        'subscription_renew'  => $subscription_renew,
        'subscription_start'  => get_date(null, 'Y-m-d H:i:s'),
        'subscription_status' => 'active',
    ], [
        'id' => $args['user_id']
    ]);

    return $result;
}