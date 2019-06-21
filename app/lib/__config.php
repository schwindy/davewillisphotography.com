<?php

function __config($var, $val = [])
{
    $db = Database::getInstance();

    if (empty($val)) {
        return $db->get_row("SELECT val FROM config WHERE var='$var'")['val'];
    }

    $match = $db->get_row("SELECT * FROM config WHERE var='$var'");
    if (empty($match)) {
        $db->insert('config', [
            'var' => $var,
            'val' => $val,
        ]);
    } else {
        $db->update('config', [
            'val' => $val,
        ], [
            'var' => $var
        ]);
    }

    return true;
}