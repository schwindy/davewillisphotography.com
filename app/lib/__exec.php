<?php

/** Execute a command via CLI | Be careful, Skippy.
 * @param $cmd
 * @param $path
 * @param $sudo
 * @return mixed Output | bool
 */
function __exec($cmd, $path = "", $sudo = false)
{
    $json = '';
    $output = [];

    if (!empty($path)) {
        $cmd = "$path $cmd";
    }
    if (!empty($sudo)) {
        $cmd = "sudo $cmd";
    }
    exec("$cmd", $output);

    foreach ($output as $line) {
        $json .= $line;
    }

    $clean = json_decode($json);
    if (empty($clean)) {
        return $output;
    }

    return $clean;
}