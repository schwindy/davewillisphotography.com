<?php
function bootstrap()
{
    if (!defined("BASE_PATH")) {
        define("BASE_PATH", dirname(dirname(__FILE__)) . "/");
    }
    require_once(BASE_PATH . 'app/__init.php');
}