<?php

function __cache_get($key)
{
    include "/tmp/$key";

    return isset($val) ? $val : false;
}

function __cache_set($key, $val)
{
    $val = var_export($val, true);
    $val = str_replace('stdClass::__set_state', '(object)', $val);
    $tmp = "/tmp/$key." . uniqid('', true) . '.tmp';
    file_put_contents($tmp, '<?php $val = ' . "$val;", LOCK_EX);
    rename($tmp, "/tmp/$key");
}