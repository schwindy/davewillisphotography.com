<?php
/** ACL function that will redirect to the home page if a user is not an admin user
 * @param $auth_file - File path to redirect_to() on acl failure
 * @param $auth_redirect - File path to redirect_to() after successful login
 * @return string
 */
function acl_require_admin($auth_redirect = 'admin/home', $auth_file = 'admin/login')
{
    acl_require_user();
    $db = Database::getInstance();
    $user = $db->get_row("SELECT * FROM users WHERE id='$_SESSION[user_id]' AND account_type='admin'");
    if (empty($user)) {
        redirect_to("/$auth_file?redirect=" . str_replace('?', '&', urlencode($auth_redirect)));
    }

    return $user['id'];
}

/** ACL function that requires that the User does not have an account and is an "anon"
 * @param string $fail_redirect
 * @return bool
 */
function acl_require_anon($fail_redirect = '/user/')
{
    $user = get_user();
    if (!empty($user)) {
        redirect_to($fail_redirect);
    }

    return true;
}

/** ACL function that will redirect to the subscription page if a subscription is not found for the user
 * @param string $fail
 * @param string $pending
 * @return string
 */
function acl_require_subscription($fail = '/user/plans?expired=true', $pending = '/user/plans?pending=true')
{
    $user_id = acl_require_user();
    $user = get_user($user_id);
    if (empty($user)) {
        redirect_to('/logout');
    }
    if (empty($user['subscription_status'])) {
        redirect_to($fail);
    }
    if ($user['subscription_status'] === 'inactive') {
        redirect_to($fail);
    }
    if ($user['subscription_status'] === 'pending') {
        redirect_to($pending);
    }

    return $user['id'];
}

/** ACL function that will redirect to the subscription page if a User does not have an active paid subscription.
 * @param string $fail_redirect
 * @return string
 */
function acl_require_subscription_paid($fail_redirect = '/user/plans?paid=true')
{
    $user_id = acl_require_subscription();
    $user = get_user($user_id);
    if ($user['account_type'] === 'free') {
        redirect_to($fail_redirect);
    }

    return $user['id'];
}

/** ACL function that will redirect to login if a user is not defined in the session
 * @param string $auth_file - File path to redirect_to() on acl failure
 * @param string $auth_redirect - File path to redirect_to() after successful login
 * @return string
 */
function acl_require_user($auth_redirect = 'home', $auth_file = 'login')
{
    $user = get_user();
    if (empty($user)) {
        redirect_to("/$auth_file?redirect=" . urlencode($auth_redirect));
    }

    return $user['id'];
}