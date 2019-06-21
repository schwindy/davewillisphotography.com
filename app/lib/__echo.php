<?php

function br()
{
    echo "\n</br>";
}

function console($message, $data)
{
    echo __log($message, $data);
}

function echoJSON($data)
{
    echo json_encode($data);
    nl();
    exit;
}

function nl($msg = '')
{
    echo "$msg\n";
}

function pre($str)
{
    return "<pre>" . $str . "</pre>";
}

function ppre($obj)
{
    return pre(print_r($obj, true));
}

function vpre($obj)
{
    return pre(var_export($obj, true));
}