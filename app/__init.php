<?php

function __kekPHP_init()
{
    // Define Absolute Path of this Application's Directory (default: dirname(__DIR__))
    if (!defined('BASE_PATH')) {
        define('BASE_PATH', realpath('../') . '/');
    }

    // Define Absolute Path of BASE_PATH/app/ (default: this folder)
    if (!defined('APP_PATH')) {
        define('APP_PATH', BASE_PATH . 'app/');
    }

    // Initialize Global Constants
    require(APP_PATH . '_constant.php');

    // Activate Garbage Collection
    require(APP_PATH . '_garbage.php');

    // Start Default Autoloader
    // @TODO: Document Flow
    require(APP_PATH . '_autoload.php');
}

try {
    __kekPHP_init();
} catch (Exception $e) {
    new APIResponse(-1, "Initialization | FAILURE");
}