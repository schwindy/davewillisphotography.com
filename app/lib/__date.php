<?php

function get_date($time = null, $format = 'Y-m-d H:i:s')
{
    if (empty($time)) {
        return date($format);
    }

    return date($format, strtotime($time));
}