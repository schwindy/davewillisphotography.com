<?php

function __kekPHP_autoload()
{
    // Autoload Custom Files
    $files = [
        LIB_PATH . "example/autoload.php"
    ];
    foreach ($files as $file) {
        if (file_exists($file)) {
            include_once($file);
        }
    }

    $load = ["__kekPHP"];
    foreach ($load as $dir) {
        foreach (glob(APP_PATH . "classes/$dir/depends/*.php") as $file) {
            require_once($file);
        }
        foreach (glob(APP_PATH . "classes/$dir/*.php") as $file) {
            require_once($file);
        }
    }

    // Autoload Other Classes
    foreach (glob(APP_PATH . "classes/*.php") as $file) {
        require_once($file);
    }

    // Autoload Lib Methods
    foreach (glob(APP_PATH . "lib/*.php") as $file) {
        include_once($file);
    }

    // Autoload Config
    foreach (glob(CONFIG_PATH . "*.php") as $file) {
        if (strpos($file, "/config/global.php") !== false) {
            continue;
        }
        include_once($file);
    }

    // Autoload Private Config
    foreach (glob(CONFIG_PATH . "private/*.php") as $file) {
        include_once($file);
    }

    // Load Global Config
    require(CONFIG_PATH . "global.php");

    // Autoload App Methods
    foreach (glob(APP_PATH . "methods/*.php") as $file) {
        include_once($file);
    }

    // Autoload World Methods
    foreach (glob(WORLD_PATH . "*.php") as $file) {
        include_once($file);
    }

    __error();
    if (!defined('IS_LOADED')) {
        define('IS_LOADED', 'true');
        set_include_path(WEBROOT);
        date_default_timezone_set('America/Denver');
        header('Content-Language: en-US');
    }
}

__kekPHP_autoload();