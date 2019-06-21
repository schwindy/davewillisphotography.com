<?php

function save_config()
{
    foreach ($_REQUEST as $key => $val) {
        if ($key === 'PHPSESSID') {
            continue;
        }
        if ($key === '_gat') {
            continue;
        }
        if ($key === '_ga') {
            continue;
        }
        if ($key === '_gali') {
            continue;
        }
        $fields[$key] = $val;
    }

    foreach ($fields as $var => $val) {
        __config($var, $val);
    }

    redirect_to('/admin/kek/config');
}