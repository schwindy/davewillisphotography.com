<?php
header("Content-Type: application/json");
if (!defined('IS_LOADED')) {
    require('bootstrap.php');
    bootstrap();
}

// Parse argv into $_REQUEST
if (!empty($argv)) {
    for ($i = 1; $i < count($argv); $i++) {
        $arg = explode("=", $argv[$i]);
        $_REQUEST[$arg[0]] = $arg[1];
    }
}

// Parse Request
$url_components = explode('/', $_SERVER['REQUEST_URI']);
$route = "";
$routes = [];
$next = false;
foreach ($url_components as $key => $val) {
    if ($next) {
        break;
    }
    if (empty($val) || $val === 'php' || $val === 'portal') {
        continue;
    }
    if (strpos($val, "?") !== false) {
        $val = substr($val, 0, strpos($val, "?"));
        $next = true;
    }

    $routes[] = $val;
    $route .= "$val/";
}

$route = str_lreplace("/", "", $route);
if (substr($_REQUEST['run'] ?? "", 1) === '/') {
    $_REQUEST['run'] = substr($_REQUEST['run'] ?? "", 1);
}
if (empty($_REQUEST['run'])) {
    $_REQUEST['run'] = $route;
}

if (empty($_REQUEST['run'])) {
    new APIResponse(0, "Missing parameter: run");
}
if (get_global_val('status') !== "running") {
    new APIResponse(0, SITE_NAME . " is offline");
}

$file = PORTAL_PATH . $_REQUEST['run'] . '.php';
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