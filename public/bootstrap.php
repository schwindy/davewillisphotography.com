<?php
function bootstrap()
{
    if (!defined('DS')) {
        define('DS', DIRECTORY_SEPARATOR);
    }

    if (!defined('BASE_PATH')) {
        define('BASE_PATH', realpath('../') . DS);
    }
    require_once(BASE_PATH . 'app' . DS . '__init.php');
}