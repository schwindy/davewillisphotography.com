<?php

function __kekPHP_constant()
{
    if (!defined('DS')) {
        define('DS', DIRECTORY_SEPARATOR);
    }

    if (!defined('APP_PATH')) {
        define('APP_PATH', BASE_PATH . 'app' . DS);
    }
    if (!defined('BIN_PATH')) {
        define('BIN_PATH', BASE_PATH . 'bin' . DS);
    }
    if (!defined('CONFIG_PATH')) {
        define('CONFIG_PATH', BASE_PATH . 'config' . DS);
    }
    if (!defined('CRON_PATH')) {
        define('CRON_PATH', BASE_PATH . 'cron' . DS);
    }
    if (!defined('ELEMENTS_PATH')) {
        define('ELEMENTS_PATH', BASE_PATH . 'elements' . DS);
    }
    if (!defined('ENVIRONMENT_CONFIG_PATH')) {
        define('ENVIRONMENT_CONFIG_PATH', CONFIG_PATH . "env" . DS);
    }
    if (!defined('ENVIRONMENT_DEFAULT_FILE')) {
        define('ENVIRONMENT_DEFAULT_FILE', ENVIRONMENT_CONFIG_PATH . "live.php");
    }
    if (!defined('ENVIRONMENT_PATH')) {
        define('ENVIRONMENT_PATH', dirname(BASE_PATH));
    }
    if (!defined('LIB_PATH')) {
        define('LIB_PATH', BASE_PATH . 'lib' . DS);
    }
    if (!defined('LOG_PATH')) {
        define('LOG_PATH', BASE_PATH . 'logs' . DS);
    }
    if (!defined('VENDOR_PATH')) {
        define('VENDOR_PATH', BASE_PATH . 'vendor' . DS);
    }
    if (!defined('WEBROOT')) {
        define('WEBROOT', BASE_PATH . 'public' . DS);
    }

    if (!defined('PORTAL_PATH')) {
        define('PORTAL_PATH', APP_PATH . 'portal' . DS);
    }
    if (!defined('THREAD_PATH')) {
        define('THREAD_PATH', APP_PATH . 'threads' . DS);
    }
    if (!defined('WORLD_PATH')) {
        define('WORLD_PATH', APP_PATH . 'world' . DS);
    }

    if (!defined('CSS_PATH')) {
        define('CSS_PATH', '/css/');
    }
    if (!defined('CSS_PATH_FULL')) {
        define('CSS_PATH_FULL', WEBROOT . 'css/');
    }
    if (!defined('IMG_PATH')) {
        define('IMG_PATH', '/img/');
    }
    if (!defined('IMG_PATH_FULL')) {
        define('IMG_PATH_FULL', WEBROOT . 'img/');
    }
    if (!defined('JS_PATH')) {
        define('JS_PATH', '/js/');
    }
    if (!defined('JS_PATH_FULL')) {
        define('JS_PATH_FULL', WEBROOT . 'js/');
    }

    if (!defined('KEK_CSS_PATH')) {
        define('KEK_CSS_PATH', CSS_PATH_FULL . '__kek.min.css.php');
    }
    if (!defined('KEK_JS_PATH')) {
        define('KEK_JS_PATH', JS_PATH_FULL . '__kek.min.js.php');
    }

    if (!defined('KEK_OS')) {
        define('KEK_OS', strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'WIN' : 'UNIX');
    }
}

__kekPHP_constant();