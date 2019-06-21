<?php

function get_user($user_id = '')
{
    $db = Database::getInstance();

    if (empty($user_id)) {
        if (empty($_SESSION['user_id'])) {
            return false;
        }
        $user_id = $_SESSION['user_id'];
    }

    $user = $db->get_row("SELECT *" . " FROM users" . " WHERE id='$user_id'");

    if (empty($user)) {
        $user = [];
    }

    return $user;
}

function get_user_id_by_key_pair($public = '', $secret = '')
{
    $db = Database::getInstance();

    if (empty($public) || empty($secret)) {
        return false;
    }

    $pub =
        $db->get_row("SELECT user_id" . " FROM __keys" . " WHERE obj_id='cryptolytics'" . " AND type='capi_key'" . " AND data='$public'")['user_id'];

    $sec =
        $db->get_row("SELECT user_id" . " FROM __keys" . " WHERE obj_id='cryptolytics'" . " AND type='capi_secret'" . " AND data='$secret'")['user_id'];

    if (empty($pub) || $pub !== $sec) {
        return false;
    }

    return $sec;
}