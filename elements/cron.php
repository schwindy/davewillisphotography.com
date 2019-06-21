<?php

function __kekPHP_cron()
{
    if (!empty($_REQUEST['base_path'])) {
        define('BASE_PATH', $_REQUEST['base_path']);
    }

    if (!defined('IS_LOADED')) {
        require('bootstrap.php');
        bootstrap();
    }

    // ini_set("memory_limit", "10G");
    // ini_set("post_max_size", "8G");
    // ini_set("upload_max_filesize", "8G");

    if (empty($_REQUEST['run'])) {
        new APIResponse(0, "Missing parameter: run");
    }
    if (get_global_val('status') !== "running" && empty($_REQUEST['override'])) {
        new APIResponse(0, SITE_NAME . " is offline");
    }

    $file = CRON_PATH . "$_REQUEST[run].php";
    if (!file_exists($file)) {
        new APIResponse(0, "File does not exist: $_REQUEST[run]");
    }

    require_once($file);

    if (strrpos($_REQUEST['run'], '/') === false) {
        $run = $_REQUEST['run'];
    } else {
        $run = substr($_REQUEST['run'], strrpos($_REQUEST['run'], '/') + 1);
    }

    if (!function_exists($run)) {
        new APIResponse(0, "Function does not exist: $run");
    }

    call_user_func($run);

    return new Response(1, "Success!");
}

function __kekPHP_cron_echo($json)
{
    header("Content-Type: application/json");
    if (empty($json) || !$json->status || empty($json->data)) {
        exit;
    }

    echo $json->data;
}

function __kekPHP_cron_init()
{
    /* Cron Job Router */
    try {
        $json = __kekPHP_cron();
        __kekPHP_cron_echo($json);
        exit;
    } catch (Exception $e) {
        return new Response(-1, "Router | FAILURE");
    }
}

// Parse $argv for Cron Jobs only :D
if (!empty($argv)) {
    for ($i = 1; $i < count($argv); $i++) {
        $arg = explode("=", $argv[$i]);
        if (empty($arg) || empty($arg[0]) || empty($arg[1])) {
            continue;
        }

        $_REQUEST[$arg[0]] = $arg[1];
    }
}

__kekPHP_cron_init();