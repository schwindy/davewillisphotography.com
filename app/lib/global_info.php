<?php

function get_global_val($var)
{
    $db = Database::getInstance();
    $global = $db->get_row("SELECT * FROM global_info WHERE var='$var'");
    if (empty($global)) {
        return false;
    }

    return $global['val'];
}

function set_global_val($var, $val)
{
    $db = Database::getInstance();
    $match = $db->get_row("SELECT * FROM global_info WHERE var='$var'");
    if (empty($match)) {
        return $db->insert('global_info', ['var' => $var, 'val' => $val,]);
    } else {
        return $db->update('global_info', ['val' => $val,], ['var' => $var]);
    }
}