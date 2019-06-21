<?php

function generateCryptoCode($len)
{
    $crypto = "";
    for ($i = 0; $i < $len; $i++) {
        $case = rand(0, 1);
        if ($case === 0) {
            $char = chr(rand(97, 122));
        } else {
            $char = chr(rand(65, 90));
        }
        $crypto = $crypto . $char;
    }

    return $crypto;
}

function generate_asset_id($user_id, $length = 16)
{
    return microtime(true) . "-" . generateCryptoCode($length) . "_$user_id";
}

function generate_mysql_id($length = 16)
{
    return str_replace('.', '_', microtime(true)) . "-" . generateCryptoCode($length);
}

function generate_thread_id($length = 12)
{
    return str_replace('.', '_', microtime(true)) . "-" . generateCryptoCode($length);
}

function generate_job_id($length = 16)
{
    return microtime(true) . "-" . generateCryptoCode($length);
}

function generate_stream_id($source_name, $stream_title, $stream_date = null)
{
    if (empty($stream_date)) {
        $stream_date = get_date();
    }

    return "$source_name($stream_title)($stream_date)";
}

function generate_user_id($length = 16)
{
    $db = Database::getInstance();
    $id = generateCryptoCode($length);
    $match = $db->get_row("SELECT * FROM users WHERE id='$id';");
    $attempts = 0;
    while (!empty($match)) {
        $id = generateCryptoCode($length);
        $match = $db->get_row("SELECT * FROM users WHERE id='$id';");
        if (empty($match)) {
            break;
        }
        $attempts++;
        if ($attempts > 50) {
            return false;
        }
    }

    return $id;
}