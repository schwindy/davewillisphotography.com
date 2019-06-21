<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
if (!defined('IS_LOADED')) {
    require('bootstrap.php');
    bootstrap();
}

if (!empty($argv)) {
    for ($i = 1; $i < count($argv); $i++) {
        $arg = explode("=", $argv[$i]);
        $_REQUEST[$arg[0]] = $arg[1];
    }
}

if (empty($_REQUEST['run'])) {
    new APIResponse(0, "Missing parameter: run");
}
if (get_global_val('status') !== "running") {
    new APIResponse(0, SITE_NAME . " is offline");
}

$file = WORLD_PATH . $_REQUEST['run'] . '.php';
if (!file_exists($file)) {
    new APIResponse(0, "File does not exist");
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
exit;