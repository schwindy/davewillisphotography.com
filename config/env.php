<?php

// Order matters a lot
$environments = [
    "davewillisphotography.com" => "Application.master.",
];

$loaded = false;
if (empty($environments)) {
    $env = strpos(ENVIRONMENT_PATH, "dev.") === false ? "live" : "dev";
    $env_config = ENVIRONMENT_CONFIG_PATH . "$env.php";
    if (!defined('ENVIRONMENT')) {
        define('ENVIRONMENT', $env);
    }

    if (file_exists($env_config)) {
        include_once($env_config);
    } else {
        if ($env === "development") {
            echo "config/env: No Dev Config Found\n";
        } else {
            require_once(ENVIRONMENT_CONFIG_PATH . "dev.php");
        }
    }
} else {
    foreach ($environments as $host => $env) {
        if (strpos(ENVIRONMENT_PATH, $host) !== false) {
            $mode = strpos(ENVIRONMENT_PATH, "dev.$host") === false ? "live" : "dev";
            $env = $env . $mode;
            if (!defined('ENVIRONMENT')) {
                define('ENVIRONMENT', $env);
            }
            if (!defined('ENVIRONMENT_MODE')) {
                define('ENVIRONMENT_MODE', $mode);
            }

            $env_config = ENVIRONMENT_CONFIG_PATH . "$env.php";
            if (file_exists($env_config)) {
                include_once($env_config);
            } else {
                if ($env === "development") {
                    echo "config/env: No Dev Config Found\n";
                } else {
                    require_once(ENVIRONMENT_CONFIG_PATH . "dev.php");
                }
            }

            $loaded = true;
            break;
        }
    }
}

if (!$loaded) {
    //echo "\nconfig/env: Warning: Environment configuration not found! $env | Using default: " . ENVIRONMENT_DEFAULT_FILE;
    require_once(ENVIRONMENT_DEFAULT_FILE);
}