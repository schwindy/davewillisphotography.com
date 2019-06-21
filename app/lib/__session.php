<?php
/** Initialize a new Session */
function start_session()
{
    if (session_status() != PHP_SESSION_ACTIVE && empty(session_id())) {
        session_start();
    }
}

/** Retrieve the User ID of the current Session
 * @return string|bool
 */
function session_user_id()
{
    $user = get_user();
    if (empty($user)) {
        return false;
    }

    return $user['id'];
}

/** Login tasks
 * @param string $user_id
 * @param string $source (optional)
 * @param string $id_field (optional)
 * @return string|bool
 */
function session_login($user_id, $source = 'database', $id_field = 'id')
{
    $db = Database::getInstance();
    if (!defined("SESSION_LOADED")) {
        start_session();
    }

    if (empty($id_field)) {
        return false;
    }

    $user = get_user($user_id);
    if (empty($user)) {
        return false;
    }
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_login_time'] = time();
    $_SESSION['user_source'] = $source;
    $_SESSION['user_type'] = empty($user['type']) ? 'unknown' : $user['type'];
    $db->update('users', ['last_login' => get_date()], [$id_field => $user_id]);

    define("SESSION_LOADED", true);

    return $user_id;
}

/** End the current Session
 * @param string $path (optional)
 */
function end_session($path = '/')
{
    session_unset();
    session_destroy();
    if (strpos($path, '/login') === false) {
        header('Location: /');
    }
}

start_session();