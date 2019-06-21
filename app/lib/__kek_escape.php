<?php
function __kek_escape($data = '')
{
    if (empty($data)) {
        return true;
    }
    $data = htmlspecialchars_decode(strtolower($data));
    $data = str_replace("%20", " ", $data);
    //if(strpos($data, "select ") !== strrpos($data, "select "))return false;
    if (strpos($data, " union ") !== false) {
        return false;
    }
    if (strpos($data, "sleep(") !== false) {
        return false;
    }
    if (strpos($data, "information_schema") !== false) {
        return false;
    }
    if (strpos($data, "performance_schema") !== false) {
        return false;
    }

    return $data;
}