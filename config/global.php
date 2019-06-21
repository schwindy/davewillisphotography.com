<?php

// Company Information
if (!defined('COMPANY_ADDRESS')) {
    define('COMPANY_ADDRESS', __config('COMPANY_ADDRESS'));
}
if (!defined('COMPANY_LOCATION')) {
    define('COMPANY_LOCATION', __config('COMPANY_LOCATION'));
}
if (!defined('COMPANY_NAME')) {
    define('COMPANY_NAME', __config('COMPANY_NAME'));
}
if (!defined('COMPANY_NAME_LONG')) {
    define('COMPANY_NAME_LONG', __config('COMPANY_NAME_LONG'));
}
if (!defined('COMPANY_TYPE')) {
    define('COMPANY_TYPE', __config('COMPANY_TYPE'));
}

// Website Information
if (!defined('SITE_NAME')) {
    define('SITE_NAME', __config('SITE_NAME'));
}
if (!defined('SITE_PROTOCOL')) {
    define('SITE_PROTOCOL', __config('SITE_PROTOCOL'));
}
if (!defined('SITE_URL')) {
    define('SITE_URL', __config('SITE_URL'));
}
if (!defined('SITE_URL_NAME')) {
    define('SITE_URL_NAME', __config('SITE_URL_NAME'));
}

// Contact Information
if (!defined('SALES_EMAIL')) {
    define('SALES_EMAIL', __config('SALES_EMAIL'));
}
if (!defined('SALES_PHONE')) {
    define('SALES_PHONE', __config('SALES_PHONE'));
}
if (!defined('SUPPORT_EMAIL')) {
    define('SUPPORT_EMAIL', __config('SUPPORT_EMAIL'));
}
if (!defined('SUPPORT_PHONE')) {
    define('SUPPORT_PHONE', __config('SUPPORT_PHONE'));
}

// File Upload
if (!defined('TEMP_UPLOAD_PATH')) {
    define('TEMP_UPLOAD_PATH', WEBROOT . "docs" . DS);
}

// Sneaky Beaky
if (!defined('THIS')) {
    define('THIS', __config('THIS'));
}